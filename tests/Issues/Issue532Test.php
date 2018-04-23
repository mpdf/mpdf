<?php

namespace Issues;

class Issue532Test extends \Mpdf\BaseMpdfTest
{

	public function testUndefinedIndexT()
	{
		$this->mpdf->WriteHTML('<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<title>xxxxxxxxxxxxxxxxxxxxxxxxx</title>
	<style type="text/css">

		.table-boleto {
			width: 718px
		}

		.table-boleto td {
			border-left: 1px solid #000;
			border-top: 1px solid #000;
			vertical-align: top;
			padding: 0 3px
		}

		td.last-child {
			border-right: 1px solid #000
		}

		.linha-pontilhada {
			color: #000;
			font-size: 9px;
			width: 718px;
			border-bottom: 2px dotted #000;
			text-align: right;
			margin-bottom: 1px
		}

		.conteudo {
			text-align: left
		}

		.recibo-sacado, .rtl {
			text-align: right
		}

		.sacador {
			display: inline;
			margin-left: 5px
		}

		.bottomborder {
			border-bottom: 1px solid #000 !important
		}

		th.logobanco {
			border: none;
			padding: 5px 0
		}

		.logobanco {
			display: inline-block;
			max-width: 150px
		}

		.logocontainer {
			width: 257px;
			display: inline-block
		}

		.logobanco img {
			margin-bottom: -5px
		}

		th.codbanco {
			border: none;
			margin-left: -10px
		}

		.codbanco {
			font-size: 17px;
			border-left: 2px solid #000;
			border-right: 2px solid #000;
			margin-left: 25px;
			padding: 1px 5px;
			width: 50px
		}

	</style>
</head>
<body>
<div class="noprint info">
	<h2>Instruções de Impressão</h2>
	<ul>
		<li>Imprima em impressora jato de tinta (ink jet) ou laser em qualidade normal ou alta (Não use modo
			econômico).
		</li>
		<li>Utilize folha A4 (210 x 297 mm) ou Carta (216 x 279 mm) e margens mínimas à esquerda e à direita do
			formulário.
		</li>
		<li>Corte na linha indicada. Não rasure, risque, fure ou dobre a região onde se encontra o código de barras.
		</li>
		<li>Caso não apareça o código de barras no final, pressione F5 para atualizar esta tela.</li>
		<li>Caso tenha problemas ao imprimir, copie a sequencia numérica abaixo e pague no caixa eletrônico ou no
			internet banking:
		</li>
	</ul>
	<span class="header">Linha Digitável: 00190.00009 01234.567004 00000.327213 6 74190000007787</span>
	<span class="header">Valor: R$ 77,87</span> <br>
	<div class="linha-pontilhada" style="margin-bottom: 20px;">Recibo do sacado</div>
</div>
<table class="table-boleto" cellpadding="0" cellspacing="0" border="0"
       style="font-family:Arial !important;font-size:10px;">
	<tbody>
	<tr>
		<td valign="bottom" colspan="5" class="nopadding last-child" style="border:none;">
			<table cellpadding="0" cellspacing="0" border="0">
				<thead>
				<tr>
					<th>
						<img src="data:image/jpg;base64,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" alt="logotipo do banco">
					</th>
					<th class="codbanco">
						<div class="codbanco">001-9</div>
					</th>
					<th>
						<div class="featured"></div>
						xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx</div>
					</th>
				</tr>
				</thead>
			</table>
		</td>
	</tr>
	<tr>
		<td width="150">
			<div class="titulo title-featured">Agência/Código do Beneficiário</div>
			<table width="150" border="0" style="border-spacing: 0px;">
				<tr>
					<td style="text-align:right;border:none;">1234-6/77777777-4</td>
				</tr>
			</table>
		</td>
		<td>
			<div class="titulo title-featured">Nosso Número</div>
			<table width="100%" border="0" style="border-spacing: 0px;">
				<tr>
					<td style="text-align:right;border:none;">12345670000000327</td>
				</tr>
			</table>
		</td>
		<td width="110">
			<div class="titulo title-featured">Número do Documento</div>
			<table width="110" border="0" style="border-spacing: 0px;">
				<tr>
					<td style="text-align:right;border:none;">327</td>
				</tr>
			</table>
		</td>
		<td width="110">
			<div class="titulo title-featured">Data do documento</div>
			<table width="110" border="0" style="border-spacing: 0px;">
				<tr>
					<td style="text-align:right;border:none;">30/01/2018</td>
				</tr>
			</table>
		</td>
		<td width="180" class="last-child">
			<div class="titulo title-featured">Vencimento</div>
			<table width="180" border="0" style="border-spacing: 0px;">
				<tr>
					<td class="featured" style="text-align:right;border:none;">29/01/2018</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<div class="titulo title-featured">Pagador</div>
			<div class="conteudo">Vitória e Nathan Plásticos e Embalagens Ltda.</div>
		</td>
		<td width="180" class="last-child">
			<div class="titulo title-featured">Valor</div>
			<table width="180" border="0" style="border-spacing: 0px;">
				<tr>
					<td class="featured" style="text-align:right;border:none;">77,87</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style="width: 400px; font-size: 8px; border-bottom: none; border-right: none; border-left: none; text-align:left;">
			&nbsp;xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
		</td>
		<td colspan="4" style="border-bottom: none; border-right: none; border-left: none; text-align:right;">
			AUTENTICAÇÃO MECÂNICA / RECIBO DO PAGADOR
		</td>
	</tr>
	</tbody>
</table>

<div class="linha-pontilhada" style="margin-bottom: 20px;"></div>

<table class="table-boleto" cellpadding="0" cellspacing="0" border="0"
       style="font-family:Arial !important;font-size:10px;">
	<tbody>
	<tr>
		<td valign="bottom" colspan="8" class="nopadding last-child" style="border:none;">
			<table cellpadding="0" cellspacing="0" border="0">
				<thead>
				<tr>
					<th class="logobanco">
						<img src="data:image/jpg;base64,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" alt="logotipo do banco">
					</th>
					<th class="codbanco">
						<div class="codbanco">001-9</div>
					</th>
					<th>
						<div class="linha-digitavel">00190.00009 01234.567004 00000.327213 6 74190000007787</div>
					</th>
				</tr>
				</thead>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="7" width="400">
			<div class="titulo">Local de Pagamento</div>
			<div class="conteudo">Pago em qualquer banco ou agência lotérica até o vencimento</div>
		</td>
		<td width="180" class="last-child">
			<div class="titulo">Vencimento</div>
			<table width="180" border="0" style="border-spacing: 0px;">
				<tr>
					<td class="featured" style="text-align:right;border:none;">29/01/2018</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="7">
			<div class="titulo">Beneficiáro</div>
			<div class="conteudo">xxxxxxxxxxxxxxxxxxxxxxxx</div>
		</td>
		<td width="180" class="last-child">
			<div class="titulo">Agência/Código Beneficiário</div>
			<table width="180" border="0" style="border-spacing: 0px;">
				<tr>
					<td style="text-align:right;border:none;">1234-6/77777777-4</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td width="110" colspan="2">
			<div class="titulo">Data do documento</div>
			<table width="110" border="0" style="border-spacing: 0px;">
				<tr>
					<td style="text-align:center;border:none;">30/01/2018</td>
				</tr>
			</table>
		</td>
		<td width="140" colspan="2">
			<div class="titulo">Nº documento</div>
			<table width="140" border="0" style="border-spacing: 0px;">
				<tr>
					<td style="border:none;">327</td>
				</tr>
			</table>
		</td>
		<td width="65">
			<div class="titulo">Espécie doc.</div>
			<table width="65" border="0" style="border-spacing: 0px;">
				<tr>
					<td style="text-align:center;border:none;">DS</td>
				</tr>
			</table>
		</td>
		<td width="55">
			<div class="titulo">Aceite</div>
			<table width="55" border="0" style="border-spacing: 0px;">
				<tr>
					<td style="text-align:center;border:none;">N</td>
				</tr>
			</table>
		</td>
		<td width="110">
			<div class="titulo">Data processamento</div>
			<table width="110" border="0" style="border-spacing: 0px;">
				<tr>
					<td style="text-align:center;border:none;">30/01/2018</td>
				</tr>
			</table>
		</td>
		<td width="180" class="last-child">
			<div class="titulo">Nosso número</div>
			<table width="180" border="0" style="border-spacing: 0px;">
				<tr>
					<td style="text-align:right;border:none;">12345670000000327</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<div class="titulo">Uso do banco</div>
			<div class="conteudo"></div>
		</td>

		<td width="65">
			<div class="titulo">Carteira</div>
			<table width="65" border="0" style="border-spacing: 0px;">
				<tr>
					<td style="text-align:center;border:none;">21</td>
				</tr>
			</table>
		</td>
		<td width="65">
			<div class="titulo">Espécie</div>
			<table width="65" border="0" style="border-spacing: 0px;">
				<tr>
					<td style="text-align:center;border:none;">R$</td>
				</tr>
			</table>
		</td>
		<td width="135" colspan="2">
			<div class="titulo">Quantidade</div>
			<table width="135" border="0" style="border-spacing: 0px;">
				<tr>
					<td style="border:none;"></td>
				</tr>
			</table>
		</td>
		<td width="110">
			<div class="titulo">(X) Valor</div>
			<div class="conteudo" style="width: 100%;"></div>
		</td>
		<td width="180" class="last-child">
			<div class="titulo">(=) Valor do Documento</div>
			<table width="180" border="0" style="border-spacing: 0px;">
				<tr>
					<td class="featured" style="text-align:right;border:none;">77,87</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="7" height="23px">
			<div class="titulo">Instruções (Todas as informações deste bloqueto são de exclusiva responsabilidade do
				cedente)
			</div>
			<div class="conteudo instrucoes">&nbsp;&nbsp;Contribuição Assistêncial 01/2018</div>
		</td>
		<td width="180" class="last-child">
			<div class="titulo">(-) Descontos / Abatimentos</div>
			<table width="180" border="0" style="border-spacing: 0px;">
				<tr>
					<td style="text-align:right;border:none;"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="7" height="24px" style="border-top:none;">
			<div class="conteudo instrucoes">Teste1</div>
			<div class="conteudo instrucoes">Teste2</div>
		</td>
		<td width="180" class="last-child">
			<div class="titulo">(-) Outras deduções</div>
			<table width="180" border="0" style="border-spacing: 0px;">
				<tr>
					<td style="text-align:right;border:none;"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="7" height="24px" style="border-top:none;">
			<div class="conteudo instrucoes">Teste3</div>
			<div class="conteudo instrucoes">Teste4</div>
		</td>
		<td width="180" class="last-child">
			<div class="titulo">(+) Mora / Multa</div>
			<table width="180" border="0" style="border-spacing: 0px;">
				<tr>
					<td style="text-align:right;border:none;"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="7" height="24px" style="border-top:none;">
			<div class="conteudo instrucoes">Teste5</div>
			<div class="conteudo instrucoes">Teste6</div>
		</td>
		<td width="180" class="last-child">
			<div class="titulo">(+) Outros acréscimos</div>
			<table width="180" border="0" style="border-spacing: 0px;">
				<tr>
					<td style="text-align:right;border:none;"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td colspan="7" height="24px" style="border-top:none;">
			<div class="conteudo instrucoes">Teste7</div>
			<div class="conteudo instrucoes">Teste8</div>
		</td>
		<td width="180" class="last-child">
			<div class="titulo">(=) Valor cobrado</div>
			<table width="180" border="0" style="border-spacing: 0px;">
				<tr>
					<td style="text-align:right;border:none;"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="conteudo" style="border-bottom: 1px solid #000000;">
			<div class="titulo">Pagador:</div>

		</td>
		<td colspan="6" class="last-child"
		    style="border-bottom: 1px solid #000000; border-left:none; border-right:none;">
			<div class="conteudo">Vitória e Nathan Plásticos e Embalagens Ltda.</div>
			<div class="conteudo">Quadra QNQ 1 Conjunto 6, 167 -</div>
			<div class="conteudo">72270-106 - Brasília - DF</div>
			<div class="conteudo">&nbsp;&nbsp;Luiza e Joana Assessoria ME</div>
		</td>
		<td class="last-child" style="border-bottom: 1px solid #000000; border-left:none;">
			<div class="titulo" style="margin-top: 30px">CNPJ/CPF: 94.669.696/0001-97</div>
		</td>
	</tr>
	<tr>
		<td colspan="6" class="last-child" style="padding-top: 5px; border:none;">

		</td>
		<td colspan="2" class="last-child" style="padding-top: 2px; border:none;text-align:right;">
			<div class="conteudo rtl">Ficha de Compensação / Autenticação mecânica</div>
		</td>
	</tr>
	</tbody>
</table>
</body>
</html>');

		$output = $this->mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}

	public function testUndefinedIndexL()
	{
		$this->mpdf->WriteHTML('<div class="container">
<div class="row">
    <div class="col-xs-6">
        <div class="pull-left">
            <img src="<?= $logo ?>" alt="logo">
        </div>
    </div>
    <div class="col-xs-6">
        <div class="pull-right">
            <p>Invoice #</p>

            <p></p>
        </div>
    </div>
</div>
<hr />
<div class="row">
    <div class="col-xs-6">
        <div class="pull-left">
            <p>Company</p>

            <p>Adress</p>

            <p>Country</p>

            <p>Email</p>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="pull-right">
            <p>Date:</p>

            <p>User ID:</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
            <table class="table table-responsive">
                <thead>
                <th>ID</th>
                <th>Type</th>
                <th>Description</th>
                <th>Amount</th>
                <th>VAT</th>
                <th>Total</th>
                </thead>
                <tbody>
                </tbody>
            </table>
    </div>
</div>

<div class="row">
    <div class="col-xs-6">
        <div class="pull-left">
            <p>All charges are in Euro</p>
            <p>For any inquiries please contact <a href="mailto:xx@xx.io">support@xx.io</a></p>
        </div>
    </div>
    <div class="col-xs-6">
        <div class="pull-right">
        </div>
    </div>
</div>
<hr />
</div>');

		$output = $this->mpdf->output('', 'S');
		$this->assertStringStartsWith('%PDF-', $output);
	}


}
