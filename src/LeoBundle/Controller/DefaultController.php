<?php

namespace LeoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('LeoBundle:Default:index.html.twig');
    }
    public function pdftestAction(Request$request, $id)
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
	  
	    $this->returnPDFResponseFromHTML($html, $id);

        //return $this->render('TestBundle:Default:pdftest.html.php');
    }
    public function returnPDFResponseFromHTML($html, $id){
        //set_time_limit(30); uncomment this line according to your needs
        // If you are not in a controller, retrieve of some way the service container and then retrieve it
        //$pdf = $this->container->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        //if you are in a controlller use :
        $pdf = $this->get("white_october.tcpdf")->create('vertical', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        // set document information
        $user = $this->getUser();
		//$pdf->SetCreator($user->getPrenom());
		//$pdf->SetAuthor($user->getUsername());
		$pdf->SetTitle('TCPDF Example 008');
		$pdf->SetSubject('TCPDF Tutorial');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
		//variable utilisé
		$nomemp=($user->getNom());
		$prenomemp=($user->getPrenom());
		$signatureimg='<img src="http://ekladata.com/BXrTO8zY1TkQwYJPJLdNDBztHwk@350x263.jpg" alt="" />';
		$PDF_HEADER_LOGO='logoclem.png';
		$dateval=date(' d/m/Y ');
		// set default header data

		$PDF_HEADER_STRING = "Généré par Clemence";// .$user->getPrenom();//.$user->getNom;
		$pdf->SetHeaderData($PDF_HEADER_LOGO, 10, 'Contrat de prêt', $PDF_HEADER_STRING);

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
		$duhtml="<p style=\"text-align: center;\"><strong>CONTRAT DE PRÊT D'ARGENT ENTRE PARTICULIERS<br /></strong></p>";
		$duhtml.="<p style=\"text-align: left;\"><strong>ENTRE</strong><br /></p>";
		$duhtml.="<p style=\"text-align: left;\">________</span></span>, né(e) le ________</span></span> et résidant à l'adresse suivante : ________</p>";
		$duhtml.="<p style=\"text-align: left;\">ci-après désigné(e) \"le prêteur\",<br /></p>";
		$duhtml.="<p style=\"text-align: left;\"><strong>ET</strong><br /></p>";
		$duhtml.="<p style=\"text-align: left;\">________</span></span>, né(e) le ________</span></span> et résidant à l'adresse suivante : ________</p>";
		$duhtml.="<p style=\"text-align: left;\">ci-après désigné(e) \"emprunteur\", </p>";
		$duhtml.="<p style=\"text-align: left;\"><br /><br /></p>";
		$duhtml.="<p style=\"text-align: left;\"><strong>ARTICLE 1. OBJET DU CONTRAT<br /></strong></p>";
		$duhtml.="<p style=\"text-align: left;\">Le présent contrat a pour objet de formaliser le prêt de somme d'argent du prêteur à l'emprunteur et de préciser les conditions et modalités de remboursement de ce prêt.</p>";
		$duhtml.="<p style=\"text-align: left;\">Le présent contrat est conclu sous les conditions ordinaires et de droit en matière de prêt.</p>";
		$duhtml.="<p style=\"text-align: left;\">La signature du présent contrat vaut reconnaissance formelle par l'emprunteur que les fonds lui ont été remis par le prêteur.</p>";
		$duhtml.="<p style=\"text-align: left;\">Ainsi, à ce jour, le prêteur remet à l'emprunteur en guise de prêt soumis aux articles 1892 et suivants du Code civil, la somme de ________ € (________ euros).</p>";
		$duhtml.="<p style=\"text-align: left;\"><strong><br />ARTICLE 2. MODALITÉS DE REMBOURSEMENT</strong></p>";
		$duhtml.="<p style=\"text-align: left;\">Le prêt objet du présent contrat est consenti par le prêteur à l'emprunteur à titre gratuit.</p>";
		$duhtml.="<p>L'emprunteur s'engage à rembourser la somme prêtée dans son intégralité, au plus tard le ________</p>";
		$duhtml.="<p style=\"text-align: left;\">L'emprunteur aura la faculté de se libérer de tout ou partie du présent prêt avant l'échéance du terme, à la condition toutefois pour ce dernier de prévenir le prêteur au moins 2 (deux) mois à l'avance et par écrit, à son domicile. </p>";
		$duhtml.="<p style=\"text-align: left;\">Conformément à l'article 1899 du Code civil, le prêteur ne pourra en aucun cas demander le remboursement anticipé du prêt présentement consenti.</p>";
		$duhtml.="<p>En cas de décès de l'emprunteur avant le remboursement de la somme prêtée dans son intégralité, les héritiers et représentants de ce dernier seront, envers le prêteur, solidairement tenus des obligations résultant du présent contrat. Les sommes dues par l'emprunteur en vertu du présent contrat deviendront immédiatement exigibles sans qu'une mise en demeure préalable ne soit nécessaire. </p>";
		$duhtml.="<p style=\"text-align: left;\"><strong><br />ARTICLE 3. INEXÉCUTION DU CONTRAT</strong></p>";
		$duhtml.="<p style=\"text-align: left;\"><strong>3.1 Pénalités de retard</strong></p>";
		$duhtml.="<p style=\"text-align: left;\">Les retards dans le remboursement du prêt entraîneront l'exigibilité du taux d'intérêt légal et majoration consécutive de la fraction de la somme exigible à la date donnée, 10 (dix) jours après mise en demeure de payer restée infructueuse, envoyée par le prêteur à l'emprunteur par courrier recommandé avec demande d'avis de réception.</p>";
		$duhtml.="<p style=\"text-align: left;\"><strong><br />3.2 Déchéance du terme</strong></p>";
		$duhtml.="<p style=\"text-align: left;\">La somme prêtée deviendra de plein droit exigible, si bon semble au prêteur, 15 (quinze) jours après l'envoi par le prêteur d'une lettre de mise en demeure restée sans suite, et qui ferait référence à la présente clause, en cas d'inexécution par l'emprunteur de toute obligation résultant du présent contrat.</p>";
		$duhtml.="<p style=\"text-align: left;\"><strong><br />ARTICLE 4. FRAIS</strong></p>";
		$duhtml.="<p style=\"text-align: left;\">Tous les frais, droits et émoluments du présent contrat et ceux qui en seront la conséquence, ainsi que, le cas échéant, le coût de tout renouvellement d'inscription, seront supportés par l'emprunteur, qui s'y oblige.</p>";
		$duhtml.="<p style=\"text-align: left;\"><br /><br /><br />Fait à Paris via Clemence, le" .$dateval. "en 2,00 exemplaires.</p>";

	        
        $pdf->AddPage();
        //$text = 'Le Blabla du contrat ici, ici aussi, ici aussi...<br></br><br></br><dd> <b>Signature de :</b> ' .$nomemp.' '.$prenomemp.' <br></br><br></br><br></br><br></br><br></br><br></br> <b>Signature de :</b> ' .$nomemp.' '.$prenomemp;
		$pdf->writeHTML($duhtml, true, 0, true, 0);
		// set cell padding
		$pdf->setCellPaddings(1, 1, 1, 1);
		// set cell margins
		$pdf->setCellMargins(1, 1, 1, 1);

		$txt =  $nomemp.' '.$prenomemp;
		$pdf->SetFillColor(255, 255, 255);
		// Vertical alignment
		//$pdf->MultiCell(80, 50, 'Signature de : '.$txt, 1, 'J', 1, 0, '', '', true, 0, false, true, 40);
		//$pdf->MultiCell(55, 40, '[VERTICAL ALIGNMENT - MIDDLE] '.$txt, 1, 'J', 1, 0, '', '', true, 0, false, true, 40, 'M');
		//$pdf->MultiCell(80, 50, 'Signature de : '.$txt, 1, 'J', 1, 1, '', '', true, 0, false, true, 40);
		//$pdf->writeHTMLCell(100, 50, 10, 10, 'Lorem ipsum... <img src="/Users/LeoH/PFE-Clemence/images/Ubuntu-logo.png" /> Curabitur at porta dui...');
		$cellsignpret = '<b>Signature du prêteur :</b> <br/>' .$nomemp.' '.$prenomemp.' <br/>'.$signatureimg;
		$cellsignemp = '<b>Signature de l\'emprunteur :</b><br/> ' .$nomemp.' '.$prenomemp.' <br/>'.$signatureimg;
		$pdf->writeHTMLCell(80,'','','',$cellsignemp,1,0,1,true,"C",true );
		$pdf->writeHTMLCell(80,'','','',$cellsignpret,1,1,1,true,"C",true );		
		//$pdf->Image('/Users/LeoH/PFE-Clemence/images/Ubuntu-logo.png', 60, 50, 15, 15, 'PNG');
		//$pdf->Image('/Users/LeoH/PFE-Clemence/images/Ubuntu-logo.png', 60, 82, 15, 15, 'PNG');
		
		//$pdf->Image('/Images/Ubuntu-logo.png', 15, 140, 75, 113, 'PNG', 'http://www.tcpdf.org', '', true, 150, '', false, false, 1, false, false, false);
		
		//$pdf->image('/images/'.$filenames);
		
        
        $filename = 'ourcodeworld_pdf_demo';
        
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = '', $autopadding = true);
        $pdf->Output($filename.".pdf",'I'); // This will output the PDF as a response directly*/
	}
}
