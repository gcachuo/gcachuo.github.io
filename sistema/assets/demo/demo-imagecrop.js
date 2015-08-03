$(function(){
	//Default
	$('#crop-default').Jcrop();

	//Event Handler
	$('#crop-handler').Jcrop({
		onChange: showCoords,
		onSelect: showCoords,
		minSize:  [370, 507],
		setSelect:[ 0, 0, 370, 507 ],
		aspectRatio: 370 / 507
	});
	
	$('#crop-handler2').Jcrop({
		onChange: showCoords,
		onSelect: showCoords,
		minSize:  [85, 85],
		setSelect:[ 0, 0, 85, 85 ],
		aspectRatio: 85 / 85
	});
	
	$('#crop-handler3').Jcrop({
		onChange: showCoords,
		onSelect: showCoords,
		minSize:  [236, 236],
		setSelect:[ 0, 0, 236, 236 ],
		aspectRatio: 236 / 236
	});
	
	function showCoords(c)
	{
		$('#x1').val(c.x);
		$('#y1').val(c.y);
		$('#x2').val(c.x2);
		$('#y2').val(c.y2);
		$('#w').val(c.w);
		$('#h').val(c.h);
	};
	
	
	
	


});