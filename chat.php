<?php

	session_start();
	include_once("database.php");

	$res = mysqli_query($g_dbConnect,"select * from message order by id");
	$rownum = mysqli_num_rows($res);
	
	for($i=0;$i<$rownum;$i++)
	{
		$row = mysqli_fetch_array($res);
		
		echo "
			<div style='
				border: 1px solid black;
				background-color:#dddce8;
				
				padding-top:10px;
				padding-left:10px;
				padding-bottom:10px;
				padding-right:10px;
				
			'>
				<div style='
					background-color:#bbb8ec;
					padding-top:3px;
					padding-left:3px;
					padding-bottom:3px;
					padding-right:3px;
				'>
					<div style='float:left'>
						<font color=blue>".$row['name']."</font>
					</div>
					<div style='float:right'>
						<font color=red> ".$row['ip']." ".$row['location']." </font>
						&nbsp;&nbsp;
						<font color=green> ".$row['time']."</font>
					</div>
					&nbsp;
				</div>
				<div style='height:10px'></div>
				".$row['text']."
			</div>
			<br/>
		";
	}
		
	
	echo "
	
		<!-- 新消息提示框 -->
		<div id='tip' style='display:none;'>
			<div style='
				
				/* 固定在底部 */
				overflow: hidden;
				position: fixed;
				bottom: 0;
				right: 0;
				
				/* 间隔 */
				margin-right:20px;
				margin-bottom:20px;
				
				padding-top:20px;
				
				width:200px;
				height:40px;
				
				text-align:center;
				
				background-color: white;
				border:1px solid black;
				
			'>
				<div style='color:red;display:inline-block;'>有新消息</div>
				<a href='#bottom' onclick='close_tip()'>查看 ↓</a>
			</div>
		</div>
		
		<a name='bottom'></a>
	
		<script>
			
			// 显示提示框
			function show_tip()
			{
				document.getElementById('tip').style = '';
			}
			
			// 关闭提示框
			function close_tip()
			{
				document.getElementById('tip').style = 'display:none;';
			}

			// 滚动页面到底部
			function page_down()
			{
				//window.scrollTo(0, document.documentElement.scrollHeight);
				location.hash='#bottom';
			}
			
			// 发出提示音
			function tip_music()
			{
				window.AudioContext = window.AudioContext || window.webkitAudioContext;
				var audioCtx = new AudioContext();
				var oscillator = audioCtx.createOscillator();
				var gainNode = audioCtx.createGain();
				oscillator.connect(gainNode);
				gainNode.connect(audioCtx.destination);
				oscillator.type = 'sine';
				oscillator.frequency.value = 196.00;
				gainNode.gain.setValueAtTime(0, audioCtx.currentTime);
				gainNode.gain.linearRampToValueAtTime(1, audioCtx.currentTime + 0.01);
				oscillator.start(audioCtx.currentTime);
				gainNode.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 1);
				oscillator.stop(audioCtx.currentTime + 1);
			}

		</script>
		
	";

	// 如果是获取到消息后主动刷新，则显示新消息提示框，不改变滚动条位置
	if($_SESSION['call'])
	{
		$_SESSION['call'] = false;
		
		echo "
			<script>
				show_tip();
				tip_music();
			</script>
		";
	}
	
	// 如果是用户刷新，则滚动条到底部
	//else
	//{
		
	echo "
		<script>
			page_down();
		</script>
	";
	
	//}

?>




