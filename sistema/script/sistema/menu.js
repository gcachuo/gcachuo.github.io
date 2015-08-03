window.onload = function(){
	var objs = new Array();
	objs = $("menu_list").getElementsByTagName("li");
	for(var i=0;i<objs.length;i++){
		
		if(objs[i].offsetHeight == 30){
			objs[i].onclick = function(){
				var subobjs = new Array();
				subobjs = this.getElementsByTagName("a");
				var child;
				
				for(var j=0;j<subobjs.length;j++){
					child = subobjs[j];
					if(child.offsetHeight != 30){
						if(child.offsetHeight > 0){
							new Effect.Morph(child,{duration:0.5,style:"height:0px"});
						}else{
							new Effect.Morph(child,{duration:0.5,style:"height:25px"});
						}
					}
				}
			}
		}
		
		var sublink = new Array();
		objs[i].onmouseover = function(){
			sublink = this.getElementsByTagName("a");
			if(sublink[0].offsetHeight == 30){
				sublink[0].style.paddingRight = "14px";
			}
		}
		
		objs[i].onmouseout = function(){
			sublink = this.getElementsByTagName("a");
			if(sublink[0].offsetHeight == 30){
				sublink[0].style.paddingRight = "12px";
			}
		}
	}
}