<?php
/**
 * Created by PhpStorm.
 * User: leila
 * Date: 17/12/2020
 * Time: 11:54
 */


function generatePdf($template,$filename){

    global $WKCONFIG;

    $html=$template;

    // Run wkhtmltopdf
    $descriptorspec = array(
        0 => array('pipe', 'r'), // stdin
        1 => array('pipe', 'w'), // stdout
        2 => array('pipe', 'w'), // stderr
    );

    $process = proc_open($WKCONFIG['PATH'].' -q - -', $descriptorspec, $pipes);

    // Send the HTML on stdin
    fwrite($pipes[0], $html);
    fclose($pipes[0]);

    // Read the outputs
    $pdf = stream_get_contents($pipes[1]);
    $errors = stream_get_contents($pipes[2]);

    // Close the process
    fclose($pipes[1]);

    $return_value = proc_close($process);

    // Output the results
    if ($errors) {
        http_response_code(400);
        echo "PDF generation failed ".$errors;
    } else {
        header('Content-Type: application/pdf');
        header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
        header('Pragma: public');
        header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s').' GMT');
        header('Content-Length: ' . strlen($pdf));
        header('Content-Disposition: inline; filename="' . $filename . '";');
        ob_clean();
        flush();
        echo $pdf;
    }

   // generate_pdf(render($name), $name2);


        // $this->generate_pdf(render($data),$filename);
       // $this->generate_pdf(render($this->getItems($orderId),$user,$order['deliver_date']),$filename);



}


