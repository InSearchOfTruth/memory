<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" content=" ">
	<meta name="description" content="Portfolio">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
	<link rel="stylesheet" href="css/main.css"> 
	<title>Game</title>
</head>
<body>
		
		<?php
		
		$arr = array();
		if ($handle = opendir('img')) {
			while (false !== ($file = readdir($handle))) { 
				if ($file != "." && $file != "..") { 
					//echo "$file\n"; 
					array_push($arr, $file);
				} 
			}
			closedir($handle); 
			}
			
			$json = json_encode($arr);
		?>
	<div class="wrapper">
		<div class="header" >
			<div class="start-game-btn">
				<button id="startGameBtn" onclick="startGame()">Start Game</button>
			</div>
			<div class="timer" ><span id="tenMinutes">0</span>
			<span id="minutes">0</span>:
			<span id="tenSeconds">0</span>
			<span id="seconds">0</span></div>
		</div>
		<div class="game-content" id="game">
			
		</div>
		<div class="modal-end-game" id="modal">
			<div class="modal-content">
				<h1>Congratulations! Try Again?</h1>
				<button onclick="startGame()">Yes</button><button onclick="resetPage()">No</button>
			</div>
		</div>
	</div>	
		

	<script>
	var alsl_list = <? echo $json;?>;
	console.log(alsl_list);
	
	function getValue(array,keyText) {
    for(i=0;i<array.length;i++){
        if(array[i].product_id == keyText) {
            console.log(array[i].model);
        }
    }
}

getValue(alsl_list, "28");
	var openGameBlock = [];	
	var timer = 0;
	var seconds = 0;
	var tenSeconds = 0;
	var minutes = 0;
	var tenMinutes = 0;
	var modal = document.getElementById('modal');	
		function startGame(){
			var all_list = <? echo $json;?>; //получаем изображение из папки
			var startGameBtn = document.getElementById('startGameBtn');	
			var game = document.getElementById('game');
			var arr=[1,1,2,2,3,3,4,4,5,5,6,6,7,7,8,8]; //индексы для сравнения изображений
			//обнуляем таймер
				timer = 0;
				seconds = 0;
				tenSeconds = 0;
				minutes = 0;
				tenMinutes = 0;
				modal.style.display = 'none';
				modal.style.transform = 'translateY(-900%)';
				function arr_sort(a,b){
					return Math.random() -0.5;
				}

				arr.sort(arr_sort); //перемешиваем изображение и индексы к ним
				all_list.sort(arr_sort);

				//отрисовываем поле игры
				var showGameOnPage = '<div class="game-wrapper">';
					for( var i = 0; i<16; i++){
						showGameOnPage += '<div class="show-block" data-target="'+arr[i]+'"><div class="block-closed"><img src="question/question.png"></div><div class="block-open"><img src="img/'+all_list[arr[i]]+'"></div></div>';
					}
					showGameOnPage += '</div>';
					game.innerHTML = showGameOnPage;
					startGameBtn.innerHTML = 'Restart'
				gameStep();
				
				startTimer();
		}
		//запускаем таймер
		function startTimer(){
				window.clearTimeout(timer);
				document.getElementById('seconds').innerHTML = seconds;
				document.getElementById('tenSeconds').innerHTML = tenSeconds;
				document.getElementById('minutes').innerHTML = minutes;
				document.getElementById('tenMinutes').innerHTML = tenMinutes;
				seconds++;
				if(seconds == 10){
					tenSeconds++;
					seconds = 0;
				}
				if(tenSeconds == 6){
					minutes++;
					tenSeconds = 0;
				}
				if(minutes == 10){
					tenMinutes++;
					minutes = 0;
				}
				timer = setTimeout("startTimer()", 1000);
		}
		//отслеживаем нажатие по изображению
			function gameStep(){
				var gameBlock = document.querySelectorAll('.show-block');
				for (var i = 0; i < gameBlock.length; i++){
					gameBlock[i].addEventListener('click', function(){
						if(openGameBlock.length === 2){
							return;
						}else{
							this.classList.add('open');
							checkImage();
						}
					});
				}
			}	
		//сравниваем изображение
		function checkImage(){
			openGameBlock = document.querySelectorAll('.open');		
			if(openGameBlock.length === 2){
				if(openGameBlock[0].dataset.target == openGameBlock[1].dataset.target){
					for( var i = 0; i < openGameBlock.length; i++){
						openGameBlock[i].classList.remove('open')
						openGameBlock[i].classList.add('checked')
					}
					if(document.querySelectorAll('.checked').length == 16){
						setTimeout("finishGame()", 1500);
					};
					openGameBlock = [];
				}else if(openGameBlock[0].dataset.target !== openGameBlock[1].dataset.target){
					setTimeout(function(){
						openGameBlock[0].classList.remove('open');
						openGameBlock[1].classList.remove('open');
						openGameBlock = [];
					}, 1000);
				}
			}
		}

		function finishGame(){

			window.clearTimeout(timer);
			modal.style.display = 'block';
		}
		function resetPage(){
			location.reload();
		}
	</script>
	</body>
</html>