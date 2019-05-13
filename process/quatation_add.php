<?php include_once '../config.php'; ?>
<?php header('Content-Type: text/html; charset=utf-8'); ?>
<?php
$srvsql				=	new	srvsql();
$connect_pos		=	$srvsql->connect_pos();
$date   			= 	date('Y-m-d');


	$sql		=	"
							SELECT	COUNT(quotation_date)	as	'countq'
							FROM	[CBL-POS].[dbo].[quotation_head] WHERE quotation_date='$date'
							";
	$query	=	sqlsrv_query($connect_pos,$sql) or die( 'SQL Error = '.$sql.'<hr><pre>'. 	print_r( sqlsrv_errors(), true) . '</pre>');
	$row		=	sqlsrv_fetch_array($query);
	$countq			=	$row['countq']+1;
	$quotation_no	=	date('Ym').'-'.sprintf("%03d",$countq);



	$sql			=	"
							INSERT INTO	[CBL-POS].[dbo].[quotation_head]([quotation_no],[quotation_name],[quotation_date])
							VALUES
							(
								'".$quotation_no."',
								'".$_POST['quotation_name']."',
								'".$date."'
							)
							";
	$query			=	sqlsrv_query($connect_pos,$sql) or die( 'SQL Error = '.$sql.'<hr><pre>'. 	print_r( sqlsrv_errors(), true) . '</pre>');


	echo '<pre>';
	print_r($_POST['item']);
	echo '<pre>';
	foreach ($_POST['item'] as $key => $value) {

		$itme=explode(',', $_POST['item'][$key]);
		$item_name	=	$itme[0];
		$unit_price = $itme[1];
		$sql			=	"
								INSERT INTO	[CBL-POS].[dbo].[quotation_line]([quantity],[item_name],[unit_price],[quotation_no])
								VALUES
								(
									'".$_POST['quantity'][$key]."',
									'".$item_name."',
									'".$unit_price."',
									'".$quotation_no."'
								)
								";
		$query			=	sqlsrv_query($connect_pos,$sql) or die( 'SQL Error = '.$sql.'<hr><pre>'. 	print_r( sqlsrv_errors(), true) . '</pre>');
	}

	

	foreach ($_POST['deposit_amount'] as $key => $value) {
	
		$sql			=	"
								INSERT INTO	[CBL-POS].[dbo].[deposit]([quotation_no],[deposit_date],[deposit_amount])
								VALUES
								(
									'".$quotation_no."',
									'".$_POST['deposit_date'][$key]."',
									'".$_POST['deposit_amount'][$key]."'
								)
								";
		$query			=	sqlsrv_query($connect_pos,$sql) or die( 'SQL Error = '.$sql.'<hr><pre>'. 	print_r( sqlsrv_errors(), true) . '</pre>');
	}





?>
