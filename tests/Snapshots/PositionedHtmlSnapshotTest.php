<?php

namespace Snapshots;

/**
 * @group snapshot
 */
class PositionedHtmlSnapshotTest extends Snapshot
{
	/**
	 * @return string A unique identifier / name for the snapshot
	 */
	public function getId()
	{
		return 'positioned-html';
	}

	/**
	 * Generate a PDF document by initializing the Mpdf object on $this->mpdf and
	 * loading it with content
	 *
	 * @return   void
	 * @internal Don't call any $this->mpdf->Output*() method
	 */
	public function generatePdf()
	{
		ob_start();
		?>
		<style>
			.gradient {
				border: 0.1mm solid #220044;
				background-color: #f0f2ff;
				background-gradient: linear #c7cdde #f0f2ff 0 1 0 0.5;
			}

			h4 {
				font-family: sans;
				font-weight: bold;
				margin-top: 1em;
				margin-bottom: 0.5em;
			}

			div {
				padding: 1em;
				margin-bottom: 1em;
				text-align: justify;
			}

			.myfixed1 {
				position: absolute;
				overflow: visible;
				left: 0;
				bottom: 0;
				border: 1px solid #880000;
				background-color: #FFEEDD;
				background-gradient: linear #dec7cd #fff0f2 0 1 0 0.5;
				padding: 1.5em;
				font-family: sans;
				margin: 0;
			}

			.myfixed2 {
				position: fixed;
				overflow: auto;
				right: 0;
				bottom: 0mm;
				width: 65mm;
				border: 1px solid #880000;
				background-color: #FFEEDD;
				background-gradient: linear #dec7cd #fff0f2 0 1 0 0.5;
				padding: 0.5em;
				font-family: sans;
				margin: 0;
				rotate: 90;
			}
		</style>

		<body>
		<h1>mPDF</h1>
		<h2>Floating & Fixed Position elements</h2>

		<h4>CSS "Float"</h4>
		<div class="gradient">
			Block elements can be positioned alongside each other using the CSS property float: left or right. The clear
			property can also be used, set as left|right|both. Float is only supported on block elements (i.e. not SPAN
			etc.) and is not fully compliant with the CSS specification.
			Float only works properly if a width is set for the float, otherwise the width is set to the maximum
			available (full width, or less if floats already set).
			<br/>
			Margin-right can still be set for a float:right and vice-versa.
			<br/>
			A block element next to a float has the padding adjusted so that content fits in the remaining width. Text
			next to a float should wrap correctly, but backgrounds and borders will overlap and/or lie under the floats
			in a mess.
			<br/>
			NB The width that is set defines the width of the content-box. So if you have two floats with width=50% and
			either of them has padding, margin or border, they will not fit together on the page.
		</div>

		<div class="gradient" style="float: right; width: 28%; margin-bottom: 0pt; ">
			<img src="img/tiger.webp" style="float:right" width="70"/>This is text in a &lt;div&gt; element that is
			set to float:right and width:28%. It also has an image with float:right inside. With this exception, you
			cannot nest elements with the float property set inside one another.
		</div>

		<div class="gradient" style="float: left; width: 54%; margin-bottom: 0pt; ">
			This is text in a &lt;div&gt; element that is set to float:left and width:54%.
		</div>

		<div style="clear: both; margin: 0pt; padding: 0pt; "></div>
		This is text that follows a &lt;div&gt; element that is set to clear:both.

		<h4>CSS "Position"</h4>
		At the bottom of the page are two DIV elements with position:fixed and position:absolute set

		<div class="myfixed1">
			1 Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in,
			scelerisque vitae, magna. Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in,
			scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus
			aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Sed egestas justo nec ipsum. Nulla facilisi.
			Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo.
		</div>

		<div class="myfixed2">
			2 Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in,
			scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus
			aliquet vulputate. Donec luctus. Cras euismod tellus vel leo.
		</div>

		<pagebreak />

		<div style="position:fixed; left: 0; right: 0; bottom: 0; top: 0;">
			<h1>mPDF</h1>
			<h4>Fixed-position block element with Autofit</h4>
			<div>Using the CSS properties position and overflow:auto it is possible to fit text to a single page:</div>

			<p>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p>

			<div><img src="img/tiger.webp" style="float:right; width:150px">DIV: Proin aliquet lorem id felis. Curabitur vel libero at mauris nonummy tincidunt. Donec imperdiet. Vestibulum sem sem, lacinia vel, molestie et, laoreet eget, urna. Curabitur viverra faucibus pede. Morbi lobortis. Donec dapibus. Donec tempus. Ut arcu enim, rhoncus ac, venenatis eu, porttitor mollis, dui. Sed vitae risus. In elementum sem placerat dui. Nam tristique eros in nisl. Nulla cursus sapien non quam porta porttitor. Quisque dictum ipsum ornare tortor. Fusce ornare tempus enim. </div>

			<div><img src="img/bayeux2.jpg" style="opacity: 0.5; float: left;" />DIV: Proin aliquet lorem id felis. Curabitur vel libero at mauris nonummy tincidunt. Donec imperdiet. Vestibulum sem sem, lacinia vel, molestie et, laoreet eget, urna. Curabitur viverra faucibus pede. Morbi lobortis. Donec dapibus. Donec tempus. Ut arcu enim, rhoncus ac, venenatis eu, porttitor mollis, dui. Sed vitae risus. In elementum sem placerat dui. Nam tristique eros in nisl. Nulla cursus sapien non quam porta porttitor. Quisque dictum ipsum ornare tortor. Fusce ornare tempus enim. </div>

			<blockquote>Blockquote: Maecenas arcu justo, malesuada eu, dapibus ac, adipiscing vitae, turpis. Fusce mollis. Aliquam egestas. In purus dolor, facilisis at, fermentum nec, molestie et, metus. Maecenas arcu justo, malesuada eu, dapibus ac, adipiscing vitae, turpis. Fusce mollis. Aliquam egestas. In purus dolor, facilisis at, fermentum nec, molestie et, metus.</blockquote>

			<address>Address: Vestibulum feugiat, orci at imperdiet tincidunt, mauris erat facilisis urna, sagittis ultricies dui nisl et lectus. Sed lacinia, lectus vitae dictum sodales, elit ipsum ultrices orci, non euismod arcu diam non metus.</address>

			<div><a href="dummy123456">Hyperlink (&lt;a&gt;)</a></div>
			<div><a href="#top">Hyperlink (&lt;a&gt;)</a></div>
			<div><a href="http://www.pallcare.info">Hyperlink (&lt;a&gt;)</a></div>

			<div>Styles - <tt>tt(teletype)</tt> <i>italic</i> <b>bold</b> <big>big</big> <small>small</small> <em>emphasis</em> <strong>strong</strong> <br />new lines<br>
				<code>code</code> <samp>sample</samp> <kbd>keyboard</kbd> <var>variable</var> <cite>citation</cite> <abbr>abbr.</abbr> <acronym>ACRONYM</acronym> <sup>sup</sup> <sub>sub</sub> <strike>strike</strike> <s>strike-s</s> <u>underline</u> <del>delete</del> <ins>insert</ins> <q>To be or not to be</q> <font face="sans-serif" color="#880000" size="5">font changing face, size and color</font>
			</div>

			<p style="font-size:15pt; color:#440066">Paragraph using the in-line style to determine the font-size (15pt) and colour</p>

			<h3>Testing BIG, SMALL, UNDERLINE, STRIKETHROUGH, FONT color, ACRONYM, SUPERSCRIPT and SUBSCRIPT</h3>
			<p>This is <s>strikethrough</s> in <b><s>block</s></b> and <small>small <s>strikethrough</s> in <i>small span</i></small> and <big>big <s>strikethrough</s> in big span</big> and then <u>underline and <s>strikethrough and <sup>sup</sup></s></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</p>

			<p>This is a <font color="#008800">green reference<sup>32-47</sup></font> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> then <s>Strikethrough reference<sup>32-47</sup></s> and <s>strikethrough reference<sub>32-47</sub></s></p>

			<p><big>Repeated in <u>BIG</u>: This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</big></p>

			<p><small>Repeated in small: This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</small></p>

			<p>The above repeated, but starting with a paragraph with font-size specified (7pt)</p>

			<p style="font-size:7pt;">This is <s>strikethrough</s> in block and <small>small <s>strikethrough</s> in small span</small> and then <u>underline</u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</p>

			<p style="font-size:7pt;">This is <s>strikethrough</s> in block and <big>big <s>strikethrough</s> in big span</big> and then <u>underline</u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</p>

			<p style="font-size:7pt;">This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> then <s>Strikethrough reference<sup>32-47</sup></s> and <s>strikethrough reference<sub>32-47</sub></s></p>

			<p><small>This tests <u>underline</u> and <s>strikethrough</s> when they are <s><u>used together</u></s> as they both use text-decoration</small></p>

			<p><small>Repeated in small: This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</small></p>

			<p style="font-size:7pt;"><big>Repeated in BIG but with font-size set to 7pt by in-line css: This is reference<sup>32-47</sup> and <u>underlined reference<sup>32-47</sup></u> then reference<sub>32-47</sub> and <u>underlined reference<sub>32-47</sub></u> but out of span again but <font color="#000088">blue</font> font and <acronym>ACRONYM</acronym> text</big></p>

			<p>Sed bibendum. Nunc eleifend ornare velit. Sed consectetuer urna in erat. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos hymenaeos. Mauris sodales semper metus. Maecenas justo libero, pretium at, malesuada eu, mollis et, arcu. Ut suscipit pede in nulla. Praesent elementum, dolor ac fringilla posuere, elit libero rutrum massa, vel tincidunt dui tellus a ante. Sed aliquet euismod dolor. Vestibulum sed dui. Duis lobortis hendrerit quam. Donec tempus orci ut libero. Pellentesque suscipit malesuada nisi. </p>

			<p>Praesent pharetra nulla in turpis. Sed ipsum nulla, sodales nec, vulputate in, scelerisque vitae, magna. Sed egestas justo nec ipsum. Nulla facilisi. Praesent sit amet pede quis metus aliquet vulputate. Donec luctus. Cras euismod tellus vel leo. Cras tellus. Fusce aliquet. Curabitur tincidunt viverra ligula. Fusce eget erat. Donec pede. Vestibulum id felis. Phasellus tincidunt ligula non pede. Morbi turpis. In vitae dui non erat placerat malesuada. Mauris adipiscing congue ante. Proin at erat. Aliquam mattis. </p>
		</div>

		<?php
		$html = ob_get_clean();

		$this->mpdf = new \Mpdf\Mpdf(['mode' => 'c']);
		$this->mpdf->SetBasePath(__DIR__ . '/../data');
		$this->mpdf->WriteHTML($html);
	}
}
