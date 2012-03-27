<?php
/*
    snippet(phrase,[max length],[phrase tail])
    snippetgreedy(phrase,[max length before next space],[phrase tail])

*/

function snippet($text,$length=64,$tail="...") {
    $text = trim($text);
    $txtl = strlen($text);
    if($txtl > $length) {
        for($i=1;$text[$length-$i]!=" ";$i++) {
            if($i == $length) {
                return substr($text,0,$length) . $tail;
            }
        }
        $text = substr($text,0,$length-$i+1) . $tail;
    }
    return $text;
}

// It behaves greedy, gets length characters ore goes for more

function snippetgreedy($text,$length=64,$tail="...") {
    $text = trim($text);
    if(strlen($text) > $length) {
        for($i=0;$text[$length+$i]!=" ";$i++) {
            if(!$text[$length+$i]) {
                return $text;
            }
        }
        $text = substr($text,0,$length+$i) . $tail;
    }
    return $text;
}

// The same as the snippet but removing latest low punctuation chars,
// if they exist (dots and commas). It performs a later suffixal trim of spaces

function snippetwop($text,$length=64,$tail="...") {
    $text = trim($text);
    $txtl = strlen($text);
    if($txtl > $length) {
        for($i=1;$text[$length-$i]!=" ";$i++) {
            if($i == $length) {
                return substr($text,0,$length) . $tail;
            }
        }
        for(;$text[$length-$i]=="," || $text[$length-$i]=="." || $text[$length-$i]==" ";$i++) {;}
        $text = substr($text,0,$length-$i+1) . $tail;
    }
    return $text;
}

/*
echo(snippet("this is not too long to run on the column on the left, perhaps, or perhaps yes, no idea") . "<br>");
echo(snippetwop("this is not too long to run on the column on the left, perhaps, or perhaps yes, no idea") . "<br>");
echo(snippetgreedy("this is not too long to run on the column on the left, perhaps, or perhaps yes, no idea"));
*/


function snippethtml($text, $length = 64, $suffix = '&hellip;', $isHTML = true){
    $i = 0;
    $simpleTags=array('br'=>true,'hr'=>true,'input'=>true,'image'=>true,'link'=>true,'meta'=>true);
    $tags = array();
    if($isHTML){
      preg_match_all('/<[^>]+>([^<]*)/', $text, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
      foreach($m as $o){
        if($o[0][1] - $i >= $length)
          break;
        $t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
        // test if the tag is unpaired, then we mustn't save them
        if($t[0] != '/' && (!isset($simpleTags[$t])))
          $tags[] = $t;
        elseif(end($tags) == substr($t, 1))
          array_pop($tags);
        $i += $o[1][1] - $o[0][1];
      }
    }

    // output without closing tags
    $output = substr($text, 0, $length = min(strlen($text),  $length + $i));
    // closing tags
    $output2 = (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '');

    $split = preg_split('/<.*>| /', $output, -1, PREG_SPLIT_OFFSET_CAPTURE);
    $e= end($split);
    // Find last space or HTML tag (solving problem with last space in HTML tag eg. <span class="new">)
    $pos = (int)end($e);
    // Append closing tags to output
    $output.=$output2;

    // Get everything until last space
    $one = substr($output, 0, $pos);
    // Get the rest
    $two = substr($output, $pos, (strlen($output) - $pos));
    // Extract all tags from the last bit
    preg_match_all('/<(.*?)>/s', $two, $tags);
    // Add suffix if needed
    if (strlen($text) > $length) { $one .= $suffix; }
    // Re-attach tags
    $output = $one . implode($tags[0]);

    //added to remove  unnecessary closure
    $output = str_replace('</!-->','',$output);

    return $output;
}
?>