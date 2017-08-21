<?php
header("Content-Type:text/html; charset=utf-8");
define('APP_ROOT', str_replace('\\', '/', dirname(__FILE__)));

$con = '坚持以人民为中心的发展思想，是中国共产党始终不渝的理念。十八大以来，以习近平同志为核心的党中央始终牵挂着人民群众，尤其是贫困地区的困难群众。四年多的时间里，习近平总书记的足迹，踏遍全国14个集中连片特困地区。';
function get_tags_arr($title)
    {
		require(APP_ROOT.'/pscws4.class.php');
        $pscws = new PSCWS4();
		$pscws->set_dict(APP_ROOT.'/scws/dict.utf8.xdb');
		$pscws->set_rule(APP_ROOT.'/scws/rules.utf8.ini');
		$pscws->set_ignore(true);
		$pscws->send_text($title);
		$words = $pscws->get_tops(5);
		$tags = array();
		foreach ($words as $val) {
		    $tags[] = $val['word'];
		}
		$pscws->close();
		return $tags;
}

print_r(get_tags_arr($con));

function get_keywords_str($content){
	require(APP_ROOT.'/phpanalysis.class.php');
	PhpAnalysis::$loadInit = false;
	$pa = new PhpAnalysis('utf-8', 'utf-8', false);
	$pa->LoadDict();
	$pa->SetSource($content);
	$pa->StartAnalysis( false );
	$tags = $pa->GetFinallyResult();
	return $tags;
}

print(get_keywords_str($con));