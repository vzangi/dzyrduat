<div>
<?php
function mb_ucfirst($str, $encoding='UTF-8')
{
	$str = mb_ereg_replace('^[\ ]+', '', $str);
	$str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
		   mb_substr($str, 1, mb_strlen($str), $encoding);
	return $str;
}
?><div class='content'>
		<? if ($item): ?>
			<h2 <? if ($item['sound'] != ''): ?>class='sounded' data-sound='<?=$item['sound']?>'<? endif ?>><?= $item['word']?></h2>
			
			<? if ($item['description'] != ''): ?>
				<p><?=$item['description']?></p>
			<? endif ?>
			
			<? if (count($item['translates']) > 0): ?>
			<ol>
				<? for ($translate_index = 0; $translate_index < count($item['translates']); $translate_index++) { 
					$translate = $item['translates'][$translate_index];
					// $translate = [ 'words' => ['w1', 'w2', 'w3'], 'examples' => ['ex1', 'ex2'] ];
					$t_words = $translate['words'];
					$examples = $translate['examples'];
				?>
				<li>
					<? for ($w_index = 0; $w_index < count($t_words); $w_index++) { 
						$delimiter = ',';
						if ($w_index == count($t_words)-1) {
							$delimiter = ';';
						}
						if (count($examples) == 0 
							&& $translate_index == count($item['translates'])-1 
							&& $w_index == count($t_words)-1
							) {
							$delimiter = '.';
						}
						$w = mb_strtolower ($t_words[$w_index]['translate'], 'UTF-8');
						if ($w_index == 0) {
							$w = mb_ucfirst($w);
						} ?>
						<?=$w?><?=$delimiter?> 
					<? } ?>
					
					<? for ($ex_index = 0; $ex_index < count($examples); $ex_index++) { 
						$delimiter = ';';
						if ($ex_index == count($examples)-1 && $translate_index == count($item['translates'])-1) {
							$delimiter = '.';
						} ?>
						<?=$examples[$ex_index]['example']?><?=$delimiter?> 
					<? } ?>
				</li>
				<? } ?>
			</ol>
			<? endif ?>
			
		<? endif ?>
		
		<? if ($item['image']): ?>
		<img class='image' src='/u/<?= $item['image']?>' alt='<?= $item['word']?>' />
		<? endif ?>
	</div>
	<span class='page-number'><?= $item['page']?></span>
	<img src="/p/page-bg.jpg" style="width: 100%; height: 100%;">
</div>
