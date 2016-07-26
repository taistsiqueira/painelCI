<script type="text/javascript">
    $(function(){
        $('.addimg').click(function(e){
            e.preventDefault();
            $('#modalimg').reveal({
                animation : 'none'
            });
        });
    });
</script>
<div id="modalimg" class="reveal-modal large">
    <div class="row collapse">

        <div class="collapse seven columns"> <!--7 colunas-->
            <?php echo form_input(array('name'=>'pesquisarimg', 'class'=>'buscartxt')); ?>
        </div>

        <div class="five columns"><!--5 colunas-->
            <?php echo form_button('', 'Buscar','class="buscarimg button postfix"'); ?>
            <?php echo form_button('', 'Limpar','class="limparimg button postfix alert radius"'); ?>
        </div>

    </div>
    <div class="retorno">&nbsp;</div> <!--espaço em branco -->
    <a class="close-reveal-modal">&#215;</a>
</div>