(function($){
	$.fn.slideLanmu14=function(option){
		var $this = $(this);
		if ( 0==$this.size() ) return $this;
		
		var opts = option || {};
		var $elems=$this.find("[data-rel='elem']");
		if ( 0==$elems.size() ) return $this;
		
		var $elem=$($elems.get(0));
		//单个元素高度
		opts.elemHeight = $elem.outerHeight()+parseInt($elem.css("marginTop"))+parseInt($elem.css("marginBottom"));

		opts.main = $this.find("[data-rel='main']");
		opts.parentHeight = opts.main.parent().innerHeight();
		
		opts.main.height(opts.elemHeight*Math.ceil($elems.size()/2));

		opts.loop = true===opts.loop;
		$this.data("opts", opts);
		
		/* 向上移动 */
		$this.find("[data-rel='btn_up_lanmu14']").unbind("click", moveHorizontalLanmu14).bind("click", function(){
            moveHorizontalLanmu14($this, -opts.elemHeight);
		});
		/* 向右移动 */
		$this.find("[data-rel='btn_down_lanmu14']").unbind("click", moveHorizontalLanmu14).bind("click", function(){
            moveHorizontalLanmu14($this, opts.elemHeight);
		});
		return $this;
	};
	
	/* 上下移动 */
	var moveHorizontalLanmu14=function($this, ml){
		var opts = $this.data("opts");
		if ( opts.main.is(":animated") ) return false;
		opts.marginTop = opts.marginTop || parseInt(opts.main.css("marginTop"));

		if ( ml>0 )
		{
			//第一个元素已可见
			if ( opts.marginTop>=0 )
			{
				if ( opts.loop )
				{
					return false;	//终止
				}
			}
		}
		else
		{
			//最后一个元素已可见，停止执行
			if ( opts.parentHeight-opts.marginTop>=opts.main.height()-1)
			{
				if ( opts.loop )
				{
					return false;
				}
			}
		}

		opts.marginTop += ml;
		opts.main.animate({"marginTop":opts.marginTop+"px"}, 500);

		$this.data("opts", opts);
	}
})(jQuery);