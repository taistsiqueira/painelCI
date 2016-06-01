<!DOCTYPE html>

<hmtl class="no-js" lang="pt-br">
<head>
    <meta charset="utf-8" />

    <!-- Definir a largura da janela de exibição para a largura do dispositivo para celular -->
    <meta name="viewport" content="width=device-width" />

    <title><?php if(isset($titulo)): ?>{titulo} | <?php endif; ?>{titulo_padrao}</title>

    <!-- IE Fix for HTML5 Tags -->
    <!--[if lt IE9]><!--<script src="http://html5shiv.googlecode.com/dvn/trunk/html5.js"></script><![endif]-->
    {headerinc}
</head>
<body>
    <div class="row paineladm">
        {conteudo}
    </div>
    <script src="js/foundation.min.js"></script>
    {rodape}
    {footerinc}
</body>
</hmtl>