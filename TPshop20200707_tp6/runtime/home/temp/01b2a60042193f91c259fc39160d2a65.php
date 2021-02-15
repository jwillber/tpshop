<?php /*a:1:{s:45:"../template/pc/rainbow/cart/ajax_address.html";i:1593659405;}*/ ?>
<i class="sprite_le_ri"></i>
<div class="top_leg p">
    <span class="paragraph fl"><i class="ddd"></i>收货人信息</span>
    <a id="addNewAddress" class="newadd fr address_item" href="javascript:void(0);" data-address-id="0">新增收货地址</a>
</div>
<div class="consignee-list p">
    <ul>
        <?php if(is_array($address_list) || $address_list instanceof \think\Collection || $address_list instanceof \think\Paginator): $i = 0; $__LIST__ = $address_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$address): $mod = ($i % 2 );++$i;?>
            <li class="addressItem <?php if($address['is_default'] == 1): ?>addressDefault<?php endif; ?>" data-address-id="<?php echo $address['address_id']; ?>">
                <div class="item_select_t fl"  data-province-id="<?php echo $address['province']; ?>" data-city-id="<?php echo $address['city']; ?>" data-district-id="<?php echo $address['district']; ?>" data-town-id="<?php echo $address['twon']; ?>" data-longitude="<?php echo $address['longitude']; ?>" data-latitude="<?php echo $address['latitude']; ?>">
                    <span><?php echo $address['consignee']; ?>&nbsp;<?php echo $regionList[$address['province']]; ?></span>
                    <b></b>
                </div>
                <div class="addrdetail fl">
                    <span class="addr-name" title="<?php echo $address['consignee']; ?>"><?php echo $address['consignee']; ?></span>
                    <span class="addr-info" title="<?php echo $regionList[$address['province']]; ?> <?php echo $regionList[$address['city']]; ?> <?php echo $regionList[$address['district']]; ?> <?php echo $regionList[$address['twon']]; ?> <?php echo $address['address']; ?>">
                        <?php echo $regionList[$address['province']]; ?> <?php echo $regionList[$address['city']]; ?> <?php echo $regionList[$address['district']]; ?> <?php echo $regionList[$address['twon']]; ?> <?php echo $address['address']; ?>
                    </span>
                    <span class="addr-tel" title="<?php echo $address['mobile']; ?>"><?php echo $address['mobile']; ?></span>
                    <span class="addr-default">默认地址</span>
                </div>
                <div class="opbtns_editdel">
                    <a href="javascript:void(0);" class="ftx address_set_default" data-address-id="<?php echo $address['address_id']; ?>">设为默认地址</a>
                    <a href="javascript:void(0);" data-address-id="<?php echo $address['address_id']; ?>" class="ftx address_item">编辑</a>
                    <a href="javascript:void(0);" data-address-id="<?php echo $address['address_id']; ?>" class="ftx address_delete">删除</a>
                </div>
            </li>
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>
<?php if(count($address_list) > 1): ?>
    <div class="addr-switch">
        <span>更多地址</span>
        <b></b>
    </div>

<?php endif; ?>
<script>
    $(document).ready(function(){
        address_default_init();
    });
    function address_default_init(){
        var addressDefault = $('.addressDefault');
        addressDefault.find('.item_select_t').addClass('curtr');
        $('.addr-default').hide();
        $('.address_set_default').show();
        addressDefault.find('.addr-default').show();
        addressDefault.find('.address_set_default').hide();
    }
</script>