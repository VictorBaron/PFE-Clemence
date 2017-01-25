<?php

namespace LeoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('LeoBundle:Default:index.html.twig');
    }
    public function pdftestAction()
    {
	    	// You can send the html as you want
	   //$html = '<h1>Plain HTML</h1>';

	    // but in this case we will render a symfony view !
	    // We are in a controller and we can use renderView function which retrieves the html from a view
	    // then we send that html to the user.
	    $html = $this->renderView(
	         'LeoBundle:Default:pdftest.html.php',
	         array(
	          'someDataToView' => 'Something'
	         )
	    );
	  
	    $this->returnPDFResponseFromHTML($html);

        //return $this->render('TestBundle:Default:pdftest.html.php');
    }
    public function returnPDFResponseFromHTML($html){
        //set_time_limit(30); uncomment this line according to your needs
        // If you are not in a controller, retrieve of some way the service container and then retrieve it
        //$pdf = $this->container->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        //if you are in a controlller use :
        $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set document information
        $user = $this->getUser();
		$pdf->SetCreator($user->getUsername());
		
		$pdf->SetAuthor($user->getUsername());
		$pdf->SetTitle('TCPDF Example 008');
		$pdf->SetSubject('TCPDF Tutorial');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

		// set default header data

		$PDF_HEADER_STRING = "By " .$user->getUsername();
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 008', $PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		    require_once(dirname(__FILE__).'/lang/eng.php');
		    $pdf->setLanguageArray($l);
		}

	        
        $pdf->AddPage();
        $text = 'This is a <b color="#FF0000">digitally signed document</b> using the default (example) <b>tcpdf.crt</b> certificate.<br />To validate this signature you have to load the <b color="#006600">tcpdf.fdf</b> on the Arobat Reader to add the certificate to <i>List of Trusted Identities</i>.<br /><br />For more information check the source code of this example and the source code documentation for the <i>setSignature()</i> method.<br /><br /><a href="http://www.tcpdf.org">www.tcpdf.org</a>';
		$pdf->writeHTML($text, true, 0, true, 0);
		$pdf->Image('/Users/LeoH/PFE-Clemence/images/Ubuntu-logo.png', 180, 60, 15, 15, 'PNG');
		
		//$pdf->Image('/Images/Ubuntu-logo.png', 15, 140, 75, 113, 'PNG', 'http://www.tcpdf.org', '', true, 150, '', false, false, 1, false, false, false);
		
		//$pdf->image('/images/'.$filenames);
		
        
        $filename = 'ourcodeworld_pdf_demo';
        
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->Output($filename.".pdf",'I'); // This will output the PDF as a response directly*/
	}
}
