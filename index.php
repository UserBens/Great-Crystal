<?php


// function flipHorizontal($maps, $text)
// {

//     $temp = '';
    
//     for($i=0; $i<strlen($text); $i++)
//     {
//         for($j=0; $j<sizeof($maps); $j++){

//             for($k=0; $k<sizeof($maps[$j]); $k++){

//                 if(strtolower($text[$i]) === $maps[$j][$k])
//                 {
//                     if($j === 1 || $j === 2)
//                     {
//                         $idx = $j === 1? 2 : 1;
//                     } else if($j === 0 || $j === 3) {
//                         $idx = $j === 0? 3 : 0;
//                     } else  {
//                         continue;
//                     }

//                     $temp = sizeof($maps[$idx]) >= sizeof($maps[$j]) ? $temp.$maps[$idx][$k] : $temp.$maps[$idx][0];
//                 }
//             }
//         }    
//     }

//     return strtoupper($temp);
// }


// function flipVertical($maps, $text)
// {
//     $temp = '';
    
//     for($i=0; $i<strlen($text); $i++)
//     {
//         for($j=0; $j<sizeof($maps); $j++){

//             for($k=0; $k<sizeof($maps[$j]); $k++){

//                 if(strtolower($text[$i]) === $maps[$j][$k])
//                 {
//                     $temp = $temp.$text[$i];
//                 }
//             }
//         }    
//     }

//     return $temp;
// }


// function keyboard($text, $action) {


//     $maps = [
      
//         ['1', '2', '3', '4', '5', '6', '7', '8', '9', '0'],
//         ['q', 'w', 'e', 'r', 't', 'y', 'u', 'i', 'o', 'p'],
//         ['a', 's', 'd', 'f', 'g', 'h', 'j', 'k', 'l'],
//         ['z', 'x', 'c', 'v', 'b', 'n', 'm', ',', '.'],
//     ];


//     $actionArray = explode(',', (string)$action);


//     foreach($actionArray as $el)
//     {
//         if($el === 'H') {

//             $text = flipHorizontal($maps, $text);
//         }
//         // if($el === 'V') {
            
//         //     $text = flipVertical($maps, $text);
//         // } 
//     }


//     return $text;
// }



// function main() {

//     $textFile = fopen('text.txt', "r") or die("Unable to open file!");
//     $text = fread($textFile, filesize('text.txt'));
//     $actionFile = fopen('textAction.txt', "r") or die("Unable to open file!");
//     $action = fread($actionFile, filesize('textAction.txt'));

//     return keyboard($text, $action);
// }


// echo main();


$amount = 2_000_000;
$dp = 100_000;
$installment = 3;

$amountTotal = (int)$amount - (int)$dp;

         if (($amountTotal/$installment) % 10_000 === 0) {

            $billPerMonth = (int)$amountTotal / (int)$installment;

         } else {
            
            $billPerMonth = ceil((int)$amountTotal / (int)$installment);
            $billPerMonth += (10_000 - ($billPerMonth % 10_000));
}

echo $billPerMonth . ', ' . $billPerMonth - $dp * $installment;