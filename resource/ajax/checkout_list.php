<?php include_once '../../config.php'; ?>
<?php

$srvsql			=	new	srvsql();
$connect_pos	=	$srvsql->connect_pos();

?>

	<table class="table table-hover">
		<thead>
			<tr>
				<th style="width: 20%">No</th>
				<th>Detail</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$sql		=	"
								SELECT		[Orders_No],[Orders_Status]
								FROM		[CBL-POS].[dbo].[Orders]
								WHERE		[Orders_Status]		!=	'Success'
								GROUP BY	[Orders_No],[Orders_Status]
								";
			$query		=	sqlsrv_query($connect_pos,$sql) or die( 'SQL Error = '.$sql.'<hr><pre>'. print_r( sqlsrv_errors(), true) . '</pre>');
			$i			=	0;
			while($row	=	sqlsrv_fetch_array($query,SQLSRV_FETCH_ASSOC))
			{
				?>
				<tr>
					<td>
						<a class="btn btn-link" href="#" onclick="PopupCenter('checkout_detail.php?Orders_No=<?php echo $row['Orders_No']; ?>','CBL-POS','1240','600');  ">
							<?php echo $row['Orders_No']; ?>
						</a>
					</td>
					<td>
						<?php
						$sql_detail		=	"
											SELECT		*
											FROM		[Orders]
											INNER JOIN	[Orders_detail]	ON	[Orders_detail].[Orders_No]	=	[Orders].[Orders_No]
											INNER JOIN	[item]			ON	[item].[item No_]			=	[Orders_detail].[Item_No]
											WHERE		[Orders].[Orders_No]							=	'".$row['Orders_No']."'
											";
						$query_detail	=	sqlsrv_query($connect_pos,$sql_detail) or die( 'SQL Error = '.$sql_detail.'<hr><pre>'. print_r( sqlsrv_errors(), true) . '</pre>');
						$num[$i]		=	0;
						$total[$i]		=	0;
						while($row_detail		=	sqlsrv_fetch_array($query_detail,SQLSRV_FETCH_ASSOC))
						{
							$num[$i]	+=	$row_detail['Orders_detail_Qty'];
							$total[$i]	+=	$row_detail['Orders_detail_Price'];
						}
						echo '<b>Qty: <span style="color: green">' . $num[$i] . '</span> | Total : <span style="color: green">' . $total[$i] . '</span></b> ';
						?>
					</td>
					<td>
						<?php
						if($row['Orders_Status']	==	"Hold")
						{
							?>
							<span style="font-size: 18px; background-color: #dc3545; color:white; padding: 5px; border-radius: 10px;"> hold </span>
							<?php
						}
						else
						if($row['Orders_Status']	==	"New")
						{
							?>
							<span style="font-size: 18px; background-color: #28a745; color:white; padding: 5px; border-radius: 10px;"> new </span>
							<?php
						}
						?>
					</td>
				</tr>
				<?php
				$i++;
			}
			?>
		</tbody>
	</table>

