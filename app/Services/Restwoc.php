<?php


namespace App\Services;

use Illuminate\Support\Facades\Log;

/**
 * CodeIgniter REST Class
 * 
 * This library is optimized for Laravel by Mahdi Poustini.
 *
 * Make REST requests to RESTful services with simple syntax, Based on Rest client by Phil Sturgeon
 *
 * @package         CodeIgniter
 * @subpackage      Libraries
 * @category        Libraries
 * @author          Borzou Mossavari
 * @created         2022-03-07
 */

class RESTWOC
{


    protected $supported_formats = array(
        'xml'               => 'text/xml',
        'json'              => 'application/json',
        'serialize'         => 'application/vnd.php.serialized',
        'php'               => 'text/plain',
        'csv'               => 'text/csv'
    );

    protected $auto_detect_formats = array(
        'application/xml'   => 'xml',
        'text/xml'          => 'xml',
        'application/json'  => 'json',
        'text/json'         => 'json',
        'text/csv'          => 'csv',
        'application/csv'   => 'csv',
        'application/vnd.php.serialized' => 'serialize'
    );

    protected $rest_server;
    protected $format;
    protected $mime_type;

    protected $http_auth = null;
    protected $http_user = null;
    protected $http_pass = null;
    
    protected $api_name  = 'X-API-KEY';
    protected $api_key   = null;

    protected $ssl_verify_peer  = null;
    protected $ssl_cainfo       = null;

    protected $send_cookies = null;
    protected $response_string;
    
    protected $stream_context = null;
    protected $http_headers = array();
    protected $timeout = 60;

    function __construct($config = array())
    {
        Log::debug('REST Class Initialized');

        // If a URL was passed to the library
        empty($config) OR $this->initialize($config);
    }

    function __destruct()
    {
    }

    /**
     * initialize
     *
     * @access  public
     * @author  Phil Sturgeon
     * @author  Borzou Mossavari
     * @version 1.0
     */
    public function initialize($config)
    {
        $this->rest_server = (isset($config['server'])) ? @$config['server'] : '';

        if (substr($this->rest_server, -1, 1) != '/')
        {
            $this->rest_server .= '/';
        }
        
        isset($config['send_cookies']) && $this->send_cookies = $config['send_cookies'];
        
        isset($config['api_name']) && $this->api_name = $config['api_name'];
        isset($config['api_key']) && $this->api_key = $config['api_key'];
        
        isset($config['http_auth']) && $this->http_auth = $config['http_auth'];
        isset($config['http_user']) && $this->http_user = $config['http_user'];
        isset($config['http_pass']) && $this->http_pass = $config['http_pass'];

        isset($config['ssl_verify_peer']) && $this->ssl_verify_peer = $config['ssl_verify_peer'];
        isset($config['ssl_cainfo']) && $this->ssl_cainfo = $config['ssl_cainfo'];
	isset($config['timeout']) && $this->timeout = $config['timeout'];

    }

    /**
     * get
     *
     * @access  public
     * @author  Phil Sturgeon
     * @version 1.0
     */
    public function get($uri, $params = array(), $format = NULL)
    {
        if ($params)
        {
            $uri .= '?'.(is_array($params) ? http_build_query($params) : $params);
        }

        return $this->_call('GET', $uri, NULL, $format);
    }

    /**
     * post
     *
     * @access  public
     * @author  Phil Sturgeon
     * @version 1.0
     */
    public function post($uri, $params = array(), $format = NULL)
    {
        return $this->_call('POST', $uri, $params, $format);
    }

    /**
     * put
     *
     * @access  public
     * @author  Phil Sturgeon
     * @version 1.0
     */
    public function put($uri, $params = array(), $format = NULL)
    {
        return $this->_call('PUT', $uri, $params, $format);
    }

    /**
     * patch
     *
     * @access  public
     * @author  Dmitry Serzhenko
     * @version 1.0
     */
    public function patch($uri, $params = array(), $format = NULL)
    {
        return $this->_call('PATCH', $uri, $params, $format);
    }

    /**
     * delete
     *
     * @access  public
     * @author  Phil Sturgeon
     * @version 1.0
     */
    public function delete($uri, $params = array(), $format = NULL)
    {
        return $this->_call('DELETE', $uri, $params, $format);
    }

    /**
     * api_key
     *
     * @access  public
     * @author  Phil Sturgeon
     * @version 1.0
     */
    public function api_key($key, $name = FALSE)
    {
        $this->api_key  = $key;
        
        if ($name !== FALSE)
        {
            $this->api_name = $name;
        }
        
    }

    /**
     * header
     *
     * @access  public
     * @author  David Genelid
     * @version 1.0
     */ 
    public function http_header($header, $content = NULL)
    {
            $this->http_headers[] = $content ? $header . ': ' . $content : $header;
    }   

    /**
     * language
     *
     * @access  public
     * @author  Phil Sturgeon
     * @version 1.0
     */
    public function language($lang)
    {
        if (is_array($lang))
        {
            $lang = implode(', ', $lang);
        }

        $this->http_header('Accept-Language', $lang);
    }


    /**
     * _call
     *
     * @access  protected
     * @author  Phil Sturgeon
     * @version 1.0
     */
    protected function _call($method, $uri, $params = array(), $format = NULL)
    {
        if ($format !== NULL)
        {
            $this->format($format);
        }

        $this->http_header('Accept', $this->mime_type);



        // If authentication is enabled use it
        if ($this->http_auth != '' && $this->http_user != '')
        {
            $this->http_header('Authorization', "Basic ". base64_encode($this->http_user.":".$this->http_pass));
        }
        
        // If we have an API Key, then use it
        if ($this->api_key != '')
        {
            $this->http_header($this->api_name, $this->api_key);
        }
        
        // Set the Content-Type 
        $this->http_header('Content-type', $this->mime_type);
        
        if (is_array($params))
        {
                $params = http_build_query($params, NULL, '&');
        }
        // Send cookies with curl
        if ($this->send_cookies != '')
        {
            if (is_array($_COOKIE))
            {
                $cookie = http_build_query($_COOKIE, NULL, '&');
            }else{
                $cookie = $_COOKIE;
            }
            $this->http_header('Cookie', $cookie);
        }
        
        $options = array(
            'http' => array(
                  "method" => $method,
                  "header" => implode("\r\n",$this->http_headers),
                  "content" => $params,
                  "timeout" => $this->timeout
            )
        );


        // If using ssl set the ssl verification value and cainfo
        // contributed by: https://github.com/paulyasi
        if ($this->ssl_verify_peer === FALSE)
        {
            $options['ssl'] = array(
                'verify_peer' => false,
                'verify_peername' => false                
            );
        }
        elseif ($this->ssl_verify_peer === TRUE)
        {
            $options['ssl'] = array(
                'verify_peer' => true,
                'verify_peername' => true
            );
            if(!empty($this->ssl_cainfo))
            {
               $options['ssl']['cafile'] = getcwd() . $this->ssl_cainfo;
            }
        }
        
        $this->stream_context = stream_context_create($options);

        // Execute and return the response from the REST server
        $response = file_get_contents($this->rest_server.$uri, false, $this->stream_context);
        
        if(!$response)
        {
            error_log("file_get_contents Fatal error in ".__FILE__." on ".__LINE__, 0);
        }
        
        $pattern = "/^content-type\s*:\s*(.*)$/i";
        if ($response && ($header = array_values(preg_grep($pattern, $http_response_header))) &&
            (preg_match($pattern, $header[0], $match) !== false))
        {
            $content_type = $match[1];
        }else{
            $content_type = 'application/json'; //rest default is json !
        }
        
        // Format and return
        return $this->_format_response($response,$content_type);
    }

    /**
     * initialize
     *
     * If a type is passed in that is not supported, use it as a mime type
     *
     * @access  public
     * @author  Phil Sturgeon
     * @version 1.0
     */
    public function format($format)
    {
        if (array_key_exists($format, $this->supported_formats))
        {
            $this->format = $format;
            $this->mime_type = $this->supported_formats[$format];
        }

        else
        {
            $this->mime_type = $format;
        }

        return $this;
    }

    /**
     * debug
     *
     * @access  public
     * @author  Phil Sturgeon
     * @version 1.0
     */
    public function debug()
    {

        echo "=============================================<br/>\n";
        echo "<h2>REST Debug</h2>\n";
        echo "=============================================<br/>\n";
        echo "<h3>Response</h3>\n";

        if ($this->response_string)
        {
            echo "<code>".nl2br(htmlentities($this->response_string))."</code><br/>\n\n";
        }

        else
        {
            echo "No response<br/>\n\n";
        }

    }

    /**
     * _format_response
     *
     * @access  public
     * @author  Phil Sturgeon
     * @version 1.0
     */
    protected function _format_response($response,$content_type)
    {
        $this->response_string =& $response;

        // It is a supported format, so just run its formatting method
        if (array_key_exists($this->format, $this->supported_formats))
        {
            return $this->{"_".$this->format}($response);
        }

        // Find out what format the data was returned in
        $returned_mime = $content_type;

        // If they sent through more than just mime, strip it off
        if (strpos($returned_mime, ';'))
        {
            list($returned_mime) = explode(';', $returned_mime);
        }

        $returned_mime = trim($returned_mime);

        if (array_key_exists($returned_mime, $this->auto_detect_formats))
        {
            return $this->{'_'.$this->auto_detect_formats[$returned_mime]}($response);
        }

        return $response;
    }

    /**
     * _xml
     *
     * Format XML for output
     *
     * @access  public
     * @author  Phil Sturgeon
     * @version 1.0
     */
    protected function _xml($string)
    {
        return $string;
        // return $string ? (array) simplexml_load_string($string, 'SimpleXMLElement', LIBXML_NOCDATA) : array();
    }

    /**
     * _csv
     *
     * Format HTML for output.  This function is DODGY! Not perfect CSV support but works 
     * with my REST_Controller (https://github.com/philsturgeon/codeigniter-restserver)
     *
     * @access  public
     * @author  Phil Sturgeon
     * @version 1.0
     */
    protected function _csv($string)
    {
        $data = array();

        // Splits
        $rows = explode("\n", trim($string));
        $headings = explode(',', array_shift($rows));
        foreach( $rows as $row )
        {
            // The substr removes " from start and end
            $data_fields = explode('","', trim(substr($row, 1, -1)));

            if (count($data_fields) === count($headings))
            {
                $data[] = array_combine($headings, $data_fields);
            }

        }

        return $data;
    }

    /**
     * _json
     *
     * Encode as JSON
     *
     * @access  public
     * @author  Phil Sturgeon
     * @version 1.0
     */
    protected function _json($string)
    {
        return json_decode(trim($string));
    }

    /**
     * _serialize
     *
     * Encode as Serialized array
     * 
     * @access  public
     * @author  Phil Sturgeon
     * @version 1.0
     */
    protected function _serialize($string)
    {
        return unserialize(trim($string));
    }

    /**
     * _php
     *
     * Encode raw PHP
     *
     * @access  public
     * @author  Phil Sturgeon
     * @version 1.0
     */
    protected function _php($string)
    {
        $string = trim($string);
        $populated = array();
        eval("\$populated = \"$string\";");
        return $populated;
    }

}
