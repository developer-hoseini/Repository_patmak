<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Captcha extends Controller
{
    protected $generatedCode;

    protected $code_colors = array(
        array(192, 57, 43),
        array(211, 84, 0),
        array(41, 128, 185),
        array(22, 160, 133),
        array(142, 68, 173)
    );

    //Generates an image with GD and sends it to the client.
    public function index(Request $request){

        $a = rand(1, 20);
        $b = rand(1, 20);
        $c = "+";
        $sum = $a + $b;
        $string = "{$a} + {$b}";
        $request->session()->put('captcha', $sum);

        $image = imagecreatetruecolor(80, 40);

        $c1 = $this->code_colors[rand(0, count($this->code_colors) - 1 )]; // random code color             
        $c2 = $this->code_colors[rand(0, count($this->code_colors) - 1 )]; // random code color             
        $c3 = $this->code_colors[rand(0, count($this->code_colors) - 1 )]; // random code color             

        $color1 = imagecolorallocate($image, $c1[0], $c1[1], $c1[2]);
        $color2 = imagecolorallocate($image, $c2[0], $c2[1], $c2[2]);
        $color3 = imagecolorallocate($image, $c3[0], $c3[1], $c3[2]);

        imagestring($image, 4, 10, 9, $a, $color1);
        imagestring($image, 5, 30, 9, $c, $color2);
        imagestring($image, 4, 50, 9, $b, $color3);
    
        //Turn on output buffering
        ob_start();
    
        imagepng($image);
    
        //Store the contents of the output buffer
        $buffer = ob_get_contents();
        // Clean the output buffer and turn off output buffering
        ob_end_clean();
    
        imagedestroy($image);
    
        return response($buffer, 200)->header('Content-type', 'image/png');
    
    }

}