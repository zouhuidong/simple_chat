<?php

	session_start();
	include_once("database.php");

	function PrintMessages()
	{
		global $g_dbConnect;
		$res = mysqli_query($g_dbConnect,"select * from message");
		$rownum = mysqli_num_rows($res);
		
		for($i=0;$i<$rownum;$i++)
		{
			$row = mysqli_fetch_array($res);
			echo "<div style='background-color:lightgrey'><font color=blue>".$row['name']."</font><font color=green> ".$row['time']."</font><font color=red> ".$row['ip']." ".$row['location']." </font><br/>".$row['text']."</div><br/>";
		}
	}
	
	function PageDown()
	{
		echo "
			<script>
				var t = document.body.offsetHeight;
				window.scrollTo({ top: t, left: 0 });
			</script>
		";
	}

	PrintMessages();
	PageDown();

?>

