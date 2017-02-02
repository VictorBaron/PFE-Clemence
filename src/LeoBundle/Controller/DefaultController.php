<?php

namespace LeoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use \DateTime;



class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('LeoBundle:Default:index.html.twig');
    }

    public function pdftestAction($id,Request $request)

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
		$pdf->SetCreator('Clemence');
		$pdf->SetAuthor('Clemence');
		$pdf->SetTitle('TCPDF Example 008');
		$pdf->SetSubject('TCPDF Tutorial');
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');
		
		//variable utilisé
		$em = $this->getDoctrine()->getManager();
		$offreDePret = $em->getRepository('ProjectBundle:OffreDePret')->find($id);
		$project = $offreDePret->getProject(); 
		
		$datepret = $offreDePret->getDatePret();
		$datepretstr = $datepret->format(' d/m/Y ');
		
		$dateremb = $offreDePret->getDateDebutRemboursement();
		$sommepret = $offreDePret->getSomme();
		$echeance = $offreDePret->getEcheance();
		$interets = $offreDePret->getInterets();

		$mois = $sommepret/$echeance;
		$mois= (int)$mois;
		//$datefinalremboursement = strtotime(date(" d/m/Y ", strtotime($datepretstr)) . " +1 day ");
		$datefinalremboursement=date('d/m/Y',strtotime('+'.$mois.' month',strtotime($datepret->format("Y-m-d H:i:s"))));
		

		$lender = $offreDePret->getLender();
        $user = $project->getAuthor();

		$nomemp=($user->getNom());
		$prenomemp=($user->getPrenom());
		$signatureimgemp='<img src="http://ekladata.com/BXrTO8zY1TkQwYJPJLdNDBztHwk@350x263.jpg" alt="" />';
		$addresseemp='<ul><li>'.$user->getAdresse(). ' ' .$user->getCodePostal(). ' ' .$user->getVille().'</li></ul>';
		//$date = new DateTime('2000-01-01');
		//$result = $date->format('Y-m-d H:i:s');
		$datenaissemp=$user->getDateDeNaissance();
		$newDateemp = $datenaissemp->format(' d/m/Y ');

		$nomlend=$lender->getNom();
		$prenomlend=$lender->getPrenom();
		$signatureimglend='<img src="http://ekladata.com/BXrTO8zY1TkQwYJPJLdNDBztHwk@350x263.jpg" alt="" />';
		$addresselend='<ul><li>'.$lender->getAdresse(). ' ' .$lender->getCodePostal(). ' ' .$lender->getVille().'</li></ul>';
		$datenaisslend=$lender->getDateDeNaissance();
		$newDatelend = $datenaisslend->format(' d/m/Y ');
		
		$sommeenlettre = $this->chiffre_en_lettre($sommepret,'','');

		$dateval=date(' d/m/Y ');

		$PDF_HEADER_LOGO='logoclem.png';
		
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
		$duhtml="<p style=\"text-align: center;\"><strong>CONTRAT DE PRÊT D'ARGENT ENTRE PARTICULIERS</strong></p>";
		$duhtml.="<p style=\"text-align: left;\"><strong>ENTRE LE PRETEUR :</strong></p>";
		$duhtml.="<p style=\"text-align: left;\">" .$prenomlend. " ".$nomlend.", né(e) le ".$newDatelend." et résidant à l'adresse suivante : ".$addresselend."</p>";
		$duhtml.="<p style=\"text-align: left;\"><strong>ET L'EMPRUNTEUR :</strong></p>";
		$duhtml.="<p style=\"text-align: left;\">" .$prenomemp. " ".$nomemp.", né(e) le ".$newDateemp." et résidant à l'adresse suivante : " .$addresseemp. "</p>";
		$duhtml.="<p style=\"text-align: left;\"></p>";
		$duhtml.="<p style=\"text-align: left;\"><strong>ARTICLE 1. OBJET DU CONTRAT</strong></p>";
		$duhtml.="<p style=\"text-align: left;\">Le présent contrat a pour objet de formaliser le prêt de somme d'argent du prêteur à l'emprunteur et de préciser les conditions et modalités de remboursement de ce prêt.</p>";
		$duhtml.="<p style=\"text-align: left;\">Le présent contrat est conclu sous les conditions ordinaires et de droit en matière de prêt.</p>";
		$duhtml.="<p style=\"text-align: left;\">La signature du présent contrat vaut reconnaissance formelle par l'emprunteur que les fonds lui ont été remis par le prêteur.</p>";
		$duhtml.="<p style=\"text-align: left;\">Ainsi, à ce jour, le prêteur remet à l'emprunteur en guise de prêt soumis aux articles 1892 et suivants du Code civil, la somme de ".$sommepret." € (".$sommeenlettre.").</p>";
		$duhtml.="<p style=\"text-align: left;\"><strong><br />ARTICLE 2. MODALITÉS DE REMBOURSEMENT</strong></p>";
		$duhtml.="<p style=\"text-align: left;\">Le prêt objet du présent contrat est consenti par le prêteur à l'emprunteur à titre gratuit.</p>";
		$duhtml.="<p>Le remboursement de ce prêt interviendra de la façon suivante : il sera remboursé en plusieurs fois selon l'échéancier suivant : ".$echeance." € par mois moyennant un intérêt de : ".$interets." % qui s'ajoutera au remboursement du capital prêté. </p>";
		$duhtml.="<p>L'emprunteur s'engage à rembourser la somme prêtée dans son intégralité, au plus tard le " .$datefinalremboursement. ".</p>";
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
		$duhtml.="<p style=\"text-align: left;\"><br />Fait à Paris via Clemence, le" .$datepretstr. "en 2,00 exemplaires.</p>";

	        
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
		$cellsignpret = '<b>Signature du prêteur :</b> <br/>' .$nomlend.' '.$prenomlend.' <br/>'.$signatureimglend;
		$cellsignemp = '<b>Signature de l\'emprunteur :</b><br/> ' .$nomemp.' '.$prenomemp.' <br/>'.$signatureimglend;
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



	public function chiffre_en_lettre($montant, $devise1='', $devise2='')
	{
		$sortie = '';
	    if(empty($devise1)) $dev1='euros';
	    else $dev1=$devise1;
	    if(empty($devise2)) $dev2='centimes';
	    else $dev2=$devise2;
	    $valeur_entiere=intval($montant);
	    $valeur_decimal=intval(round($montant-intval($montant), 2)*100);
	    $dix_c=intval($valeur_decimal%100/10);
	    $cent_c=intval($valeur_decimal%1000/100);
	    $unite[1]=$valeur_entiere%10;
	    $dix[1]=intval($valeur_entiere%100/10);
	    $cent[1]=intval($valeur_entiere%1000/100);
	    $unite[2]=intval($valeur_entiere%10000/1000);
	    $dix[2]=intval($valeur_entiere%100000/10000);
	    $cent[2]=intval($valeur_entiere%1000000/100000);
	    $unite[3]=intval($valeur_entiere%10000000/1000000);
	    $dix[3]=intval($valeur_entiere%100000000/10000000);
	    $cent[3]=intval($valeur_entiere%1000000000/100000000);
	    $chif=array('', 'un', 'deux', 'trois', 'quatre', 'cinq', 'six', 'sept', 'huit', 'neuf', 'dix', 'onze', 'douze', 'treize', 'quatorze', 'quinze', 'seize', 'dix sept', 'dix huit', 'dix neuf');
	        $secon_c='';
	        $trio_c='';
	    for($i=1; $i<=3; $i++){
	        $prim[$i]='';
	        $secon[$i]='';
	        $trio[$i]='';
	        if($dix[$i]==0){
	            $secon[$i]='';
	            $prim[$i]=$chif[$unite[$i]];
	        }
	        else if($dix[$i]==1){
	            $secon[$i]='';
	            $prim[$i]=$chif[($unite[$i]+10)];
	        }
	        else if($dix[$i]==2){
	            if($unite[$i]==1){
	            $secon[$i]='vingt et';
	            $prim[$i]=$chif[$unite[$i]];
	            }
	            else {
	            $secon[$i]='vingt';
	            $prim[$i]=$chif[$unite[$i]];
	            }
	        }
	        else if($dix[$i]==3){
	            if($unite[$i]==1){
	            $secon[$i]='trente et';
	            $prim[$i]=$chif[$unite[$i]];
	            }
	            else {
	            $secon[$i]='trente';
	            $prim[$i]=$chif[$unite[$i]];
	            }
	        }
	        else if($dix[$i]==4){
	            if($unite[$i]==1){
	            $secon[$i]='quarante et';
	            $prim[$i]=$chif[$unite[$i]];
	            }
	            else {
	            $secon[$i]='quarante';
	            $prim[$i]=$chif[$unite[$i]];
	            }
	        }
	        else if($dix[$i]==5){
	            if($unite[$i]==1){
	            $secon[$i]='cinquante et';
	            $prim[$i]=$chif[$unite[$i]];
	            }
	            else {
	            $secon[$i]='cinquante';
	            $prim[$i]=$chif[$unite[$i]];
	            }
	        }
	        else if($dix[$i]==6){
	            if($unite[$i]==1){
	            $secon[$i]='soixante et';
	            $prim[$i]=$chif[$unite[$i]];
	            }
	            else {
	            $secon[$i]='soixante';
	            $prim[$i]=$chif[$unite[$i]];
	            }
	        }
	        else if($dix[$i]==7){
	            if($unite[$i]==1){
	            $secon[$i]='soixante et';
	            $prim[$i]=$chif[$unite[$i]+10];
	            }
	            else {
	            $secon[$i]='soixante';
	            $prim[$i]=$chif[$unite[$i]+10];
	            }
	        }
	        else if($dix[$i]==8){
	            if($unite[$i]==1){
	            $secon[$i]='quatre-vingts et';
	            $prim[$i]=$chif[$unite[$i]];
	            }
	            else {
	            $secon[$i]='quatre-vingt';
	            $prim[$i]=$chif[$unite[$i]];
	            }
	        }
	        else if($dix[$i]==9){
	            if($unite[$i]==1){
	            $secon[$i]='quatre-vingts et';
	            $prim[$i]=$chif[$unite[$i]+10];
	            }
	            else {
	            $secon[$i]='quatre-vingts';
	            $prim[$i]=$chif[$unite[$i]+10];
	            }
	        }
	        if($cent[$i]==1) $trio[$i]='cent';
	        else if($cent[$i]!=0 || $cent[$i]!='') $trio[$i]=$chif[$cent[$i]] .' cents';
	    }
	     
	     
	$chif2=array('', 'dix', 'vingt', 'trente', 'quarante', 'cinquante', 'soixante', 'soixante-dix', 'quatre-vingts', 'quatre-vingts dix');
	    $secon_c=$chif2[$dix_c];
	    if($cent_c==1) $trio_c='cent';
	    else if($cent_c!=0 || $cent_c!='') $trio_c=$chif[$cent_c] .' cents';
	     
	    if(($cent[3]==0 || $cent[3]=='') && ($dix[3]==0 || $dix[3]=='') && ($unite[3]==1))
	        $sortie .= $trio[3]. '  ' .$secon[3]. ' ' . $prim[3]. ' million ';
	    else if(($cent[3]!=0 && $cent[3]!='') || ($dix[3]!=0 && $dix[3]!='') || ($unite[3]!=0 && $unite[3]!=''))
	        $sortie .= $trio[3]. ' ' .$secon[3]. ' ' . $prim[3]. ' millions ';
	    else
	        $sortie .= $trio[3]. ' ' .$secon[3]. ' ' . $prim[3];
	     
	    if(($cent[2]==0 || $cent[2]=='') && ($dix[2]==0 || $dix[2]=='') && ($unite[2]==1))
	        $sortie .= ' mille ';
	    else if(($cent[2]!=0 && $cent[2]!='') || ($dix[2]!=0 && $dix[2]!='') || ($unite[2]!=0 && $unite[2]!=''))
	        $sortie .= $trio[2]. ' ' .$secon[2]. ' ' . $prim[2]. ' milles ';
	    else
	        $sortie .= $trio[2]. ' ' .$secon[2]. ' ' . $prim[2];
	     
	    $sortie .= $trio[1]. ' ' .$secon[1]. ' ' . $prim[1];
	     
	    $sortie .= ' '. $dev1 .' ' ;
	     
	    if(($cent_c=='0' || $cent_c=='') && ($dix_c=='0' || $dix_c==''))
	        $sortie .= ' et z&eacute;ro '. $dev2;
	    else
	        $sortie .= $trio_c. ' ' .$secon_c. ' ' . $dev2;

	    return $sortie;
	}
}
