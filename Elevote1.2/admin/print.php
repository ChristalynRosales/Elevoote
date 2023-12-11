<?php
	include 'includes/session.php';

	function generateRow($conn){
		$contents = '';
	
		$sqlPositions = "SELECT * FROM positions ORDER BY priority ASC";
		$stmtPositions = $conn->query($sqlPositions);
	
		while($row = $stmtPositions->fetch(PDO::FETCH_ASSOC)){
			$id = $row['id'];
			$contents .= '
        		<tr>
        			<td colspan="2" align="center" style="font-size:15px;"><b>'.$row['description'].'</b></td>
        		</tr>
        		<tr>
        			<td width="80%"><b>Candidates</b></td>
        			<td width="20%"><b>Votes</b></td>
        		</tr>
        	';

        	$sqlCandidates = "SELECT * FROM candidates WHERE position_id = :position_id ORDER BY lastname ASC";
        $stmtCandidates = $conn->prepare($sqlCandidates);
        $stmtCandidates->bindParam(':position_id', $id, PDO::PARAM_INT);
        $stmtCandidates->execute();

        while($crow = $stmtCandidates->fetch(PDO::FETCH_ASSOC)){
            $sqlVotes = "SELECT * FROM votes WHERE candidate_id = :candidate_id";
            $stmtVotes = $conn->prepare($sqlVotes);
            $stmtVotes->bindParam(':candidate_id', $crow['id'], PDO::PARAM_INT);
            $stmtVotes->execute();
            $votes = $stmtVotes->rowCount();

            $contents .= '
      				<tr>
      					<td>'.$crow['lastname'].", ".$crow['firstname'].'</td>
      					<td>'.$votes.'</td>
      				</tr>
      			';

    		}

        }

		return $contents;
	}
		
	$parse = parse_ini_file('config.ini', FALSE, INI_SCANNER_RAW);
    $title = $parse['election_title'];

	require_once('../tcpdf/tcpdf.php');  
    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);  
    $pdf->SetCreator(PDF_CREATOR);  
    $pdf->SetTitle('Result: '.$title);  
    $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);  
    $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));  
    $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));  
    $pdf->SetDefaultMonospacedFont('helvetica');  
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  
    $pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);  
    $pdf->setPrintHeader(false);  
    $pdf->setPrintFooter(false);  
    $pdf->SetAutoPageBreak(TRUE, 10);  
    $pdf->SetFont('helvetica', '', 11);  
    $pdf->AddPage();  
    $content = '';  
    $content .= '
      	<h2 align="center">'.$title.'</h2>
      	<h4 align="center">Tally Result</h4>
      	<table border="1" cellspacing="0" cellpadding="3">  
      ';  
   	$content .= generateRow($conn);  
    $content .= '</table>';  
    $pdf->writeHTML($content);  
    $pdf->Output('election_result.pdf', 'I');

?>