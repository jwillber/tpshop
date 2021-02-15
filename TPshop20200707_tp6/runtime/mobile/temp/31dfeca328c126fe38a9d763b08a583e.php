<?php /*a:1:{s:49:"../template/mobile/rainbow/index/ajaxGetMore.html";i:1593659404;}*/ ?>
<?php if(is_array($favourite_goods) || $favourite_goods instanceof \think\Collection || $favourite_goods instanceof \think\Paginator): if( count($favourite_goods)==0 ) : echo "" ;else: foreach($favourite_goods as $key=>$v): ?>
	<li>
		<a href="<?php echo url('mobile/goods/goodsInfo',array('id'=>$v['goods_id'])); ?>">
			<img src="<?php echo goods_thum_images($v['goods_id'],300,300); ?>" alt="热销商品" style="width: 100%">
			<p class="mian_h2 mian_hidde"><?php echo $v['goods_name']; ?></p>
			<?php if($v['label_name']): ?>
				<span class="rx-sp"><?php echo $v['label_name']; ?></span>
				<?php else: ?>
					<span class="rx-sp"  style="height: 0.747rem;border:none"></span>
			<?php endif; ?>
			<div class="rxsp-price">
				<div>
					<!-- <span class="ro-sm">￥</span> -->
					<span class="ro-price"><b>￥</b><?php echo explode_price($v['shop_price'],0); ?>.<?php echo explode_price($v['shop_price'],1); ?></span>
				</div>
				<span class="has-sold">已售出<?php echo $v['sales_sum']+$v['virtual_sales_sum']; ?>件</span>
			</div>
		</a>
	</li>
<?php endforeach; endif; else: echo "" ;endif; ?>