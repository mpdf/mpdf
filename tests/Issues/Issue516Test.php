<?php

namespace Issues;

use Mpdf\Mpdf;

class Issue516Test extends \Mpdf\BaseMpdfTest
{
    protected function getBigTable()
    {
        return $html = '<table>
    <thead>
    <tr>
        <th rowspan="2">Magnésio (U/mL)</th>
        <th colspan="4">CI-MAGMA-02
            - Baixo
        </th>
        <th colspan="4">CI-MAGMA-01
            - Alto
        </th>
    </tr>
    <tr>
        <th align="center">Média</th>
        <th align="center">DP</th>
        <th colspan="2" align="center">Intervalo</th>
        <th align="center">Média</th>
        <th align="center">DP</th>
        <th colspan="2" align="center">Intervalo</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>Kit/Equipamento</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="2" align="center">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="2" align="center">&nbsp;</td>
    </tr>
    <tr>
        <td>
            Advia - Magon/ Azul de Xilidil # Advia 1200
        </td>
        <td>4.138</td>
        <td>0.259</td>
        <td>3.62</td>
        <td>4.66</td>
        <td>3.135</td>
        <td>0.174</td>
        <td>2.79</td>
        <td>3.48</td>
    </tr>
    <tr>
        <td>
            Advia - Magon/ Azul de Xilidil # Advia 1650/ 2400
        </td>
        <td>4.188</td>
        <td>0.246</td>
        <td>3.7</td>
        <td>4.68</td>
        <td>3.189</td>
        <td>0.082</td>
        <td>3.03</td>
        <td>3.35</td>
    </tr>
    <tr>
        <td>
            Advia - Magon/ Azul de Xilidil # Advia 1800
        </td>
        <td>4.109</td>
        <td>0.27</td>
        <td>3.57</td>
        <td>4.65</td>
        <td>3.075</td>
        <td>0.198</td>
        <td>2.68</td>
        <td>3.47</td>
    </tr>
    <tr>
        <td>
            Architect - Isocitrato desidrogenase # Architect C4000/ CI4100
        </td>
        <td>4.325</td>
        <td>0.108</td>
        <td>4.11</td>
        <td>4.54</td>
        <td>3.07</td>
        <td>0.08</td>
        <td>2.91</td>
        <td>3.23</td>
    </tr>
    <tr>
        <td>
            Architect - Isocitrato desidrogenase # Architect C8000/ CI8200
        </td>
        <td>4.287</td>
        <td>0.056</td>
        <td>4.18</td>
        <td>4.4</td>
        <td>3.088</td>
        <td>0.149</td>
        <td>2.79</td>
        <td>3.39</td>
    </tr>
    <tr>
        <td>
            Beckman AU Séries - Magon/ Azul de Xilidil # AU 400
        </td>
        <td>4.111</td>
        <td>0.162</td>
        <td>3.79</td>
        <td>4.44</td>
        <td>3.039</td>
        <td>0.271</td>
        <td>2.5</td>
        <td>3.58</td>
    </tr>
    <tr>
        <td>
            Beckman AU Séries - Magon/ Azul de Xilidil # AU 480
        </td>
        <td>4.198</td>
        <td>0.088</td>
        <td>4.02</td>
        <td>4.37</td>
        <td>3.053</td>
        <td>0.08</td>
        <td>2.89</td>
        <td>3.21</td>
    </tr>
    <tr>
        <td>
            Beckman AU Séries - Magon/ Azul de Xilidil # AU 680
        </td>
        <td>4.108</td>
        <td>0.189</td>
        <td>3.73</td>
        <td>4.49</td>
        <td>3.019</td>
        <td>0.119</td>
        <td>2.78</td>
        <td>3.26</td>
    </tr>
    <tr>
        <td>
            Bioclin Quibasa - Magon/ Azul de Xilidil # Cobas Mira/ S/ Plus/ Plus CC
        </td>
        <td>3.428</td>
        <td>0.559</td>
        <td>2.31</td>
        <td>4.55</td>
        <td>2.658</td>
        <td>0.16</td>
        <td>2.34</td>
        <td>2.98</td>
    </tr>
    <tr>
        <td>
            Bioclin Quibasa - Magon/ Azul de Xilidil # Mindray BS Séries
        </td>
        <td>3.474</td>
        <td>0.441</td>
        <td>2.59</td>
        <td>4.36</td>
        <td>2.74</td>
        <td>0.279</td>
        <td>2.18</td>
        <td>3.3</td>
    </tr>
    <tr>
        <td>
            Biotécnica - Magon/ Azul de Xilidil # BT Lyzer 150
        </td>
        <td>3.742</td>
        <td>0.357</td>
        <td>3.03</td>
        <td>4.46</td>
        <td>2.898</td>
        <td>0.142</td>
        <td>2.61</td>
        <td>3.18</td>
    </tr>
    <tr>
        <td>
            Biotécnica - Magon/ Azul de Xilidil # Cobas Mira/ S/ Plus/ Plus CC
        </td>
        <td>3.997</td>
        <td>0.901</td>
        <td>2.2</td>
        <td>5.8</td>
        <td>3.066</td>
        <td>0.572</td>
        <td>1.92</td>
        <td>4.21</td>
    </tr>
    <tr>
        <td>
            Biotécnica - Magon/ Azul de Xilidil # Mindray BS Séries
        </td>
        <td>3.868</td>
        <td>0.351</td>
        <td>3.17</td>
        <td>4.57</td>
        <td>2.957</td>
        <td>0.091</td>
        <td>2.78</td>
        <td>3.14</td>
    </tr>
    <tr>
        <td>
            Biotécnica - Magon/ Azul de Xilidil # Selectra E / Flexor E
        </td>
        <td>3.995</td>
        <td>0.158</td>
        <td>3.68</td>
        <td>4.31</td>
        <td>3.003</td>
        <td>0.07</td>
        <td>2.86</td>
        <td>3.14</td>
    </tr>
    <tr>
        <td>
            Cobas c111 - Clorofosfonazo # Cobas c111
        </td>
        <td>4.156</td>
        <td>0.271</td>
        <td>3.61</td>
        <td>4.7</td>
        <td>3.049</td>
        <td>0.175</td>
        <td>2.7</td>
        <td>3.4</td>
    </tr>
    <tr>
        <td>
            Dialab - Magon/ Azul de Xilidil # Biosystems A15/ 25
        </td>
        <td>4.004</td>
        <td>0.301</td>
        <td>3.4</td>
        <td>4.61</td>
        <td>3.038</td>
        <td>0.397</td>
        <td>2.24</td>
        <td>3.83</td>
    </tr>
    <tr>
        <td>
            Dialab - Magon/ Azul de Xilidil # Mindray BS Séries
        </td>
        <td>4.045</td>
        <td>0.224</td>
        <td>3.6</td>
        <td>4.49</td>
        <td>2.841</td>
        <td>0.288</td>
        <td>2.27</td>
        <td>3.42</td>
    </tr>
    <tr>
        <td>
            Dimension - Azul de Metiltimol # Dimension RxL Max/ Xpand
        </td>
        <td>4.187</td>
        <td>0.151</td>
        <td>3.89</td>
        <td>4.49</td>
        <td>2.98</td>
        <td>0.139</td>
        <td>2.7</td>
        <td>3.26</td>
    </tr>
    <tr>
        <td>
            Ebram Quimimag - Arsenazo # Cobas Mira/ S/ Plus/ Plus CC
        </td>
        <td>4.035</td>
        <td>0.519</td>
        <td>3</td>
        <td>5.07</td>
        <td>2.96</td>
        <td>0.262</td>
        <td>2.44</td>
        <td>3.48</td>
    </tr>
    <tr>
        <td>
            Elitech - Calmagita # Selectra E / Flexor E
        </td>
        <td>3.968</td>
        <td>0.352</td>
        <td>3.26</td>
        <td>4.67</td>
        <td>2.849</td>
        <td>0.207</td>
        <td>2.44</td>
        <td>3.26</td>
    </tr>
    <tr>
        <td>
            Elitech - Calmagita # Selectra XL / Flexor XL
        </td>
        <td>3.776</td>
        <td>0.314</td>
        <td>3.15</td>
        <td>4.4</td>
        <td>2.88</td>
        <td>0.403</td>
        <td>2.07</td>
        <td>3.69</td>
    </tr>
    <tr>
        <td>
            Gold Analisa PP - Magon/ Azul de Xilidil # Bio 200/ 2000
        </td>
        <td>4.342</td>
        <td>0.641</td>
        <td>3.06</td>
        <td>5.62</td>
        <td>2.954</td>
        <td>0.606</td>
        <td>1.74</td>
        <td>4.17</td>
    </tr>
    <tr>
        <td>
            Gold Analisa PP - Magon/ Azul de Xilidil # Mindray BS Séries
        </td>
        <td>4.36</td>
        <td>0.186</td>
        <td>3.99</td>
        <td>4.73</td>
        <td>3.4</td>

        <td>*</td>
        <td>3.4</td>
        <td>3.4</td>
    </tr>
    <tr>
        <td>
            Hitachi Cobas c311/c501/c502 2ª Geração - Magon/ Azul de Xilidil # Cobas c501
        </td>
        <td>4.18</td>
        <td>0.129</td>
        <td>3.92</td>
        <td>4.44</td>
        <td>3.083</td>
        <td>0.087</td>
        <td>2.91</td>
        <td>3.26</td>
    </tr>
    <tr>
        <td>
            Hitachi Cobas c311/c501/c502 - Clorofosfonazo # Cobas c311
        </td>
        <td>4.173</td>
        <td>0.269</td>
        <td>3.64</td>
        <td>4.71</td>
        <td>3.197</td>
        <td>0.231</td>
        <td>2.74</td>
        <td>3.66</td>
    </tr>
    <tr>
        <td>
            Hitachi Cobas c311/c501/c502 - Clorofosfonazo # Cobas c501
        </td>
        <td>4.159</td>
        <td>0.105</td>
        <td>3.95</td>
        <td>4.37</td>
        <td>3.044</td>
        <td>0.069</td>
        <td>2.91</td>
        <td>3.18</td>
    </tr>
    <tr>
        <td>
            Hitachi Cobas c701/ c702 2ª geração - Magon/ Azul de Xilidil # Cobas c702
        </td>
        <td>4.103</td>
        <td>0.088</td>
        <td>3.93</td>
        <td>4.28</td>
        <td>3.028</td>
        <td>0.028</td>
        <td>2.97</td>
        <td>3.08</td>
    </tr>
    <tr>
        <td>
            Hitachi séries/ Modular - Magon/ Azul de Xilidil # Modular
        </td>
        <td>4.048</td>
        <td>0.162</td>
        <td>3.72</td>
        <td>4.37</td>
        <td>3.01</td>
        <td>0.077</td>
        <td>2.86</td>
        <td>3.16</td>
    </tr>
    <tr>
        <td>
            Integra - Clorofosfonazo # Integra 400/ 400 plus
        </td>
        <td>4.213</td>
        <td>0.136</td>
        <td>3.94</td>
        <td>4.49</td>
        <td>3.096</td>
        <td>0.1</td>
        <td>2.9</td>
        <td>3.3</td>
    </tr>
    <tr>
        <td>
            Katal/ Interkit - Magon/ Azul de Xilidil # Biosystems A15/ 25
        </td>
        <td>4.005</td>
        <td>0.723</td>
        <td>2.56</td>
        <td>5.45</td>
        <td>2.891</td>
        <td>0.426</td>
        <td>2.04</td>
        <td>3.74</td>
    </tr>
    <tr>
        <td>
            Katal/ Interkit - Magon/ Azul de Xilidil # ChemWell/ ChemWell T
        </td>
        <td>3.508</td>
        <td>0.631</td>
        <td>2.25</td>
        <td>4.77</td>
        <td>2.756</td>
        <td>0.569</td>
        <td>1.62</td>
        <td>3.89</td>
    </tr>
    <tr>
        <td>
            Kovalent - Magon/ Azul de Xilidil # Miura/ 200/ One
        </td>
        <td>4.056</td>
        <td>0.653</td>
        <td>2.75</td>
        <td>5.36</td>
        <td>2.91</td>
        <td>0.38</td>
        <td>2.15</td>
        <td>3.67</td>
    </tr>
    <tr>
        <td>
            Kovalent - Magon/ Azul de Xilidil # Selectra E / Flexor E
        </td>
        <td>4.063</td>
        <td>0.244</td>
        <td>3.58</td>
        <td>4.55</td>
        <td>3.307</td>
        <td>0.34</td>
        <td>2.63</td>
        <td>3.99</td>
    </tr>
    <tr>
        <td>
            Labtest - Magon/ Azul de Xilidil # AU 400
        </td>
        <td>3.88</td>
        <td>0.041</td>
        <td>3.8</td>
        <td>3.96</td>
        <td>2.98</td>
        <td>0.227</td>
        <td>2.53</td>
        <td>3.43</td>
    </tr>
    <tr>
        <td>
            Labtest - Magon/ Azul de Xilidil # Bio 200/ 2000
        </td>
        <td>3.804</td>
        <td>0.615</td>
        <td>2.57</td>
        <td>5.03</td>
        <td>2.832</td>
        <td>0.67</td>
        <td>1.49</td>
        <td>4.17</td>
    </tr>
    <tr>
        <td>
            Labtest - Magon/ Azul de Xilidil # Biosystems A15/ 25
        </td>
        <td>3.488</td>
        <td>0.457</td>
        <td>2.57</td>
        <td>4.4</td>
        <td>2.638</td>
        <td>0.204</td>
        <td>2.23</td>
        <td>3.05</td>
    </tr>
    <tr>
        <td>
            Labtest - Magon/ Azul de Xilidil # BTS Séries
        </td>
        <td>4.028</td>
        <td>0.467</td>
        <td>3.09</td>
        <td>4.96</td>
        <td>2.978</td>
        <td>0.374</td>
        <td>2.23</td>
        <td>3.73</td>
    </tr>
    <tr>
        <td>
            Labtest - Magon/ Azul de Xilidil # Cobas Mira/ S/ Plus/ Plus CC
        </td>
        <td>3.757</td>
        <td>0.643</td>
        <td>2.47</td>
        <td>5.04</td>
        <td>2.934</td>
        <td>0.292</td>
        <td>2.35</td>
        <td>3.52</td>
    </tr>
    <tr>
        <td>
            Labtest - Magon/ Azul de Xilidil # CS 240
        </td>
        <td>3.808</td>
        <td>0.188</td>
        <td>3.43</td>
        <td>4.18</td>
        <td>2.776</td>
        <td>0.09</td>
        <td>2.6</td>
        <td>2.96</td>
    </tr>
    <tr>
        <td>
            Labtest - Magon/ Azul de Xilidil # Labmax 240
        </td>
        <td>3.995</td>
        <td>0.384</td>
        <td>3.23</td>
        <td>4.76</td>
        <td>2.975</td>
        <td>0.297</td>
        <td>2.38</td>
        <td>3.57</td>
    </tr>
    <tr>
        <td>
            Labtest - Magon/ Azul de Xilidil # Labmax 560
        </td>
        <td>4.116</td>
        <td>0.448</td>
        <td>3.22</td>
        <td>5.01</td>
        <td>3.021</td>
        <td>0.251</td>
        <td>2.52</td>
        <td>3.52</td>
    </tr>
    <tr>
        <td>
            Labtest - Magon/ Azul de Xilidil # Labmax Plenno
        </td>
        <td>3.934</td>
        <td>0.638</td>
        <td>2.66</td>
        <td>5.21</td>
        <td>2.982</td>
        <td>0.607</td>
        <td>1.77</td>
        <td>4.2</td>
    </tr>
    <tr>
        <td>
            Labtest - Magon/ Azul de Xilidil # Mindray BS Séries
        </td>
        <td>3.882</td>
        <td>0.916</td>
        <td>2.05</td>
        <td>5.71</td>
        <td>2.936</td>
        <td>0.645</td>
        <td>1.65</td>
        <td>4.23</td>
    </tr>
    <tr>
        <td>
            Labtest - Magon/ Azul de Xilidil # Selectra E / Flexor E
        </td>
        <td>3.893</td>
        <td>0.268</td>
        <td>3.36</td>
        <td>4.43</td>
        <td>2.987</td>
        <td>0.156</td>
        <td>2.68</td>
        <td>3.3</td>
    </tr>
    <tr>
        <td>
            Labtest - Magon/ Azul de Xilidil # Selectra XL / Flexor XL
        </td>
        <td>3.812</td>
        <td>0.444</td>
        <td>2.92</td>
        <td>4.7</td>
        <td>2.848</td>
        <td>0.267</td>
        <td>2.31</td>
        <td>3.38</td>
    </tr>
    <tr>
        <td>
            Vitros - Quelante de Cálcio # Vitros 250/ 350
        </td>
        <td>4.293</td>
        <td>0.137</td>
        <td>4.02</td>
        <td>4.57</td>
        <td>3.091</td>
        <td>0.109</td>
        <td>2.87</td>
        <td>3.31</td>
    </tr>
    <tr>
        <td>
            Vitros - Quelante de Cálcio # Vitros 5.1 FS
        </td>
        <td>4.244</td>
        <td>0.132</td>
        <td>3.98</td>
        <td>4.51</td>
        <td>3.024</td>
        <td>0.116</td>
        <td>2.79</td>
        <td>3.26</td>
    </tr>
    <tr>
        <td>
            Vitros - Quelante de Cálcio # Vitros 5600
        </td>
        <td>4.208</td>
        <td>0.153</td>
        <td>3.9</td>
        <td>4.51</td>
        <td>3.025</td>
        <td>0.121</td>
        <td>2.78</td>
        <td>3.27</td>
    </tr>
    <tr>
        <td>
            Wiener AA - Magon/ Azul de Xilidil # CM 200
        </td>
        <td>3.2</td>

        <td>*</td>
        <td>3.2</td>
        <td>3.2</td>
        <td>2.8</td>
        <td>0.113</td>
        <td>2.57</td>
        <td>3.03</td>
    </tr>
    <tr>
        <td>
            Wiener AA - Magon/ Azul de Xilidil # CT 600/ 600i
        </td>
        <td>3.9</td>

        <td>*</td>
        <td>3.9</td>
        <td>3.9</td>
        <td>2.987</td>
        <td>0.017</td>
        <td>2.95</td>
        <td>3.02</td>
    </tr>
    <tr>
        <td>
            Wiener AA - Magon/ Azul de Xilidil # Konelab 30/ 30I
        </td>
        <td>4.13</td>
        <td>0.167</td>
        <td>3.8</td>
        <td>4.46</td>
        <td>3.1</td>

        <td>*</td>
        <td>3.1</td>
        <td>3.1</td>
    </tr>
    <tr>
        <td>
            Wiener AA - Magon/ Azul de Xilidil # Konelab Séries
        </td>
        <td>3.703</td>
        <td>0.017</td>
        <td>3.67</td>
        <td>3.74</td>
        <td>2.7</td>

        <td>*</td>
        <td>2.7</td>
        <td>2.7</td>
    </tr>
    <tr>
        <td>
            Wiener AA - Magon/ Azul de Xilidil # Targa BT 3000/ BT 3000 Plus
        </td>
        <td>3.939</td>
        <td>0.474</td>
        <td>2.99</td>
        <td>4.89</td>
        <td>2.949</td>
        <td>0.275</td>
        <td>2.4</td>
        <td>3.5</td>
    </tr>
    <tr>
        <td>
            Wiener CPZ - Clorofosfonazo # Konelab Séries
        </td>
        <td>3.903</td>
        <td>0.182</td>
        <td>3.54</td>
        <td>4.27</td>
        <td>2.953</td>
        <td>0.161</td>
        <td>2.63</td>
        <td>3.28</td>
    </tr>
    <tr>
        <td colspan="9">&nbsp;</td>
    </tr>
    <tr>
        <td>Kit</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="2" align="center">&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td colspan="2" align="center">&nbsp;</td>
    </tr>
    <tr>
        <td>
            Advia - Magon/ Azul de Xilidil
        </td>
        <td>4.148</td>
        <td>0.258</td>
        <td>3.63</td>
        <td>4.66</td>
        <td>3.139</td>
        <td>0.137</td>
        <td>2.87</td>
        <td>3.41</td>
    </tr>
    <tr>
        <td>
            Architect/ Aeroset - Arsenazo
        </td>
        <td>4.229</td>
        <td>0.209</td>
        <td>3.81</td>
        <td>4.65</td>
        <td>3.054</td>
        <td>0.148</td>
        <td>2.76</td>
        <td>3.35</td>
    </tr>
    <tr>
        <td>
            Architect - Isocitrato desidrogenase
        </td>
        <td>4.294</td>
        <td>0.151</td>
        <td>3.99</td>
        <td>4.6</td>
        <td>3.086</td>
        <td>0.112</td>
        <td>2.86</td>
        <td>3.31</td>
    </tr>
    <tr>
        <td>
            Beckman AU Séries - Magon/ Azul de Xilidil
        </td>
        <td>4.116</td>
        <td>0.158</td>
        <td>3.8</td>
        <td>4.43</td>
        <td>3.02</td>
        <td>0.16</td>
        <td>2.7</td>
        <td>3.34</td>
    </tr>
    <tr>
        <td>
            Bioclin Quibasa - Magon/ Azul de Xilidil
        </td>
        <td>3.574</td>
        <td>0.55</td>
        <td>2.47</td>
        <td>4.67</td>
        <td>2.756</td>
        <td>0.412</td>
        <td>1.93</td>
        <td>3.58</td>
    </tr>
    <tr>
        <td>
            Bioclin Quibasa - Magon/Azul de Xilidil monoreagente
        </td>
        <td>3.594</td>
        <td>0.66</td>
        <td>2.27</td>
        <td>4.91</td>
        <td>2.779</td>
        <td>0.475</td>
        <td>1.83</td>
        <td>3.73</td>
    </tr>
    <tr>
        <td>
            Biosystems - Calmagita
        </td>
        <td>3.446</td>
        <td>0.77</td>
        <td>1.91</td>
        <td>4.99</td>
        <td>2.704</td>
        <td>0.451</td>
        <td>1.8</td>
        <td>3.61</td>
    </tr>
    <tr>
        <td>
            Biosystems - Magon/ Azul de Xilidil
        </td>
        <td>3.935</td>
        <td>0.441</td>
        <td>3.05</td>
        <td>4.82</td>
        <td>2.761</td>
        <td>0.445</td>
        <td>1.87</td>
        <td>3.65</td>
    </tr>
    <tr>
        <td>
            Biotécnica - Magon/ Azul de Xilidil
        </td>
        <td>3.858</td>
        <td>0.359</td>
        <td>3.14</td>
        <td>4.58</td>
        <td>2.932</td>
        <td>0.419</td>
        <td>2.09</td>
        <td>3.77</td>
    </tr>
    <tr>
        <td>
            Cobas c111 - Clorofosfonazo
        </td>
        <td>4.156</td>
        <td>0.271</td>
        <td>3.61</td>
        <td>4.7</td>
        <td>3.049</td>
        <td>0.175</td>
        <td>2.7</td>
        <td>3.4</td>
    </tr>
    <tr>
        <td>
            Dialab - Magon/ Azul de Xilidil
        </td>
        <td>4.04</td>
        <td>0.236</td>
        <td>3.57</td>
        <td>4.51</td>
        <td>2.901</td>
        <td>0.294</td>
        <td>2.31</td>
        <td>3.49</td>
    </tr>
    <tr>
        <td>
            Diasys FS - Magon/ Azul de Xilidil
        </td>
        <td>3.903</td>
        <td>0.387</td>
        <td>3.13</td>
        <td>4.68</td>
        <td>2.993</td>
        <td>0.128</td>
        <td>2.74</td>
        <td>3.25</td>
    </tr>
    <tr>
        <td>
            Dimension - Azul de Metiltimol
        </td>
        <td>4.19</td>
        <td>0.153</td>
        <td>3.88</td>
        <td>4.5</td>
        <td>2.978</td>
        <td>0.14</td>
        <td>2.7</td>
        <td>3.26</td>
    </tr>
    <tr>
        <td>
            Ebram Quimimag - Arsenazo
        </td>
        <td>4.012</td>
        <td>0.303</td>
        <td>3.41</td>
        <td>4.62</td>
        <td>2.963</td>
        <td>0.17</td>
        <td>2.62</td>
        <td>3.3</td>
    </tr>
    <tr>
        <td>
            Gold Analisa PP - Magon/ Azul de Xilidil
        </td>
        <td>3.837</td>
        <td>0.677</td>
        <td>2.48</td>
        <td>5.19</td>
        <td>3.003</td>
        <td>0.488</td>
        <td>2.03</td>
        <td>3.98</td>
    </tr>
    </tbody>
</table>';
    }

    public function testTreatmentOnUndefinedIndex()
    {
        $html = $this->getBigTable();
        $this->mpdf->WriteHTML($html);
        $this->mpdf->output('', 'S');
    }
}
