<?php

require '../vendor/autoload.php';

use Exaprint\DAL\DB;

/*
$_SERVER['exaprint_env']           = 'stage';
$_SERVER['exaprint_db_stage_host'] = '94.103.137.83';
$_SERVER['exaprint_db_stage_port'] = 1433;
$_SERVER['exaprint_db_stage_name'] = 'Exa_IsoProd';
$_SERVER['exaprint_db_stage_user'] = 'sa';
$_SERVER['exaprint_db_stage_pass'] = 'Exaprint%2011';
*/

/**/
$_SERVER['exaprint_env'] = 'prod';
$_SERVER['exaprint_db_prod_host'] = '94.103.141.141';
$_SERVER['exaprint_db_prod_port'] = 1433;
$_SERVER['exaprint_db_prod_name'] = 'Exa_Back';
$_SERVER['exaprint_db_prod_user'] = 'exa';
$_SERVER['exaprint_db_prod_pass'] = 'exa%2012';
/**/

$data = [];

// espagnol 2

$data[] = [1753, "Caja Botella con ventana L", 2];
$data[] = [1754, "Caja Botella sin ventana S", 2];
$data[] = [1755, "Caja Botella sin ventana M", 2];
$data[] = [1756, "Caja Botella sin ventana L", 2];
$data[] = [1757, "Caja envío S", 2];
$data[] = [1758, "Caja envío M", 2];
$data[] = [1759, "Caja envío L", 2];
$data[] = [1760, "Caja envío cuadrada S", 2];
$data[] = [1761, "Caja envío cuadrada M", 2];
$data[] = [1762, "Caja envío cuadrada S", 2];
$data[] = [1763, "Caja envío rectangular S", 2];
$data[] = [1764, "Caja envío rectangular M", 2];
$data[] = [1765, "Caja envío rectangular L", 2];
$data[] = [1766, "Caja chocolate S", 2];
$data[] = [1767, "Caja chocolate M", 2];
$data[] = [1768, "Caja chocolate L", 2];
$data[] = [1772, "Caja producto baja S", 2];
$data[] = [1773, "Caja producto baja M", 2];
$data[] = [1774, "Caja producto baja L", 2];
$data[] = [1776, "Caja producto alta M", 2];
$data[] = [1777, "Caja producto alta L", 2];
$data[] = [1778, "Caja expositor S", 2];
$data[] = [1779, "Caja expositor M", 2];
$data[] = [1780, "Caja expositor L", 2];
$data[] = [1783, "240 gr. papel color azul", 2];
$data[] = [1784, "240 gr. papel color fucsia", 2];
$data[] = [1785, "240 gr. papel color negro", 2];
$data[] = [1786, "240 gr. papel color rojo", 2];
$data[] = [1787, "240 gr. papel color turquesa", 2];
$data[] = [1788, "240 gr. papel color verde", 2];
$data[] = [1789, "250 gr. Chromolux negro", 2];
$data[] = [1790, "250 gr. Chromolux rojo", 2];
$data[] = [1795, "336 gr. cartón microcanal kraft marrón", 2];
$data[] = [1796, "275 gr. folding blanco estucado 1 cara", 2];
$data[] = [1812, "Pre encolado", 2];
$data[] = [1843, "Marcador blanco", 2];
$data[] = [1853, "3er. pedido", 2];
$data[] = [1862, "500 gr. lienzo Expolit M2", 2];
$data[] = [1865, "1800 gr. papel estucado blanco brillante 1 cara cotnracolada sobre cartón alveolar kraft marrón", 2];
$data[] = [1866, "1200 gr. papel estucado blanco brillante 1 cara cotnracolada sobre cartón alveolar blanco", 2];
$data[] = [1867, "1200 gr. papel estucado blanco brillante 1 cara cotnracolada sobre cartón alveolar kraft marrón", 2];
$data[] = [1868, "Cartón de 900 gr. blanco contracolado sobre carton triple canal kraft", 2];
$data[] = [1869, "Cartón de 680 gr. blanco contracolado sobre carton kraft", 2];
$data[] = [1870, "350 gr. cartón gráfico blanco estucado 1 cara", 2];
$data[] = [1871, "Módulo alto", 2];
$data[] = [1872, "Módulo central", 2];
$data[] = [1873, "Módulo bajo", 2];
$data[] = [1874, "Recarga", 2];
$data[] = [1875, "Completa", 2];
$data[] = [1877, "135 gr. estucado brillante HP", 2];
$data[] = [1878, "170 gr. estucado brillante HP", 2];
$data[] = [1879, "170 gr. estucado mate HP", 2];
$data[] = [1884, "UK 100gsm Premium Smooth", 2];
$data[] = [1893, "Mouse de madera y líquen de renos", 2];
$data[] = [1895, "Negro + 1 color ", 2];
$data[] = [1896, "Sin", 2];
$data[] = [1897, "Termorelieve", 2];
$data[] = [1898, "300 gr. cartón gráfico estucado 1 cara", 2];
$data[] = [1905, "Taurina, Cola, Manzana, Limón, Naranja, Multifrutas, Té verde, Uva", 2];
$data[] = [1919, "Forex 3mm", 2];
$data[] = [1921, "240 gr. papel color azul, fucsia, violeta, rojo, negro, oro viejo, gris", 2];
$data[] = [1922, "120 gr. papel color fucsia", 2];
$data[] = [1923, "120 gr. papel color oro viejo", 2];
$data[] = [1924, "120 gr. papel color gris", 2];
$data[] = [1925, "120 gr. papel color negro", 2];
$data[] = [1926, "120 gr. papel color rojo", 2];
$data[] = [1927, "120 gr. papel color violeta", 2];
$data[] = [1928, "185 gr. tela poliéster", 2];
$data[] = [1929, "Bomba de 12V", 2];
$data[] = [1930, "Saco de transporte", 2];
$data[] = [1931, "Sin", 2];
$data[] = [1932, "1 sillón + 1 mesa baja", 2];
$data[] = [1933, "2 sillónes + 1 mesa baja", 2];
$data[] = [1934, "2 pufs + 1 mesa baja", 2];
$data[] = [1935, "1 hamaca + 1 mesa baja", 2];
$data[] = [1936, "Estructura hinchable", 2];
$data[] = [1937, "Brillante", 2];
$data[] = [1938, "Sin", 2];
$data[] = [1939, "De 1 a 4 cantos redondos", 2];
$data[] = [1940, "Sin", 2];
$data[] = [1941, "Oro en caliente", 2];
$data[] = [1942, "Plata en caliente", 2];
$data[] = [1943, "1 perforación de 4mm lado del grapado", 2];
$data[] = [1944, "1 perforación de 4mm lado opuesto a el grapado", 2];
$data[] = [1945, "Doming: vinilo autoadhesivo + resina poliuretano bi-compuesta + cola termosellable", 2];
$data[] = [1946, "Doming: vinilo espejo autoadhesivo + resina poliuretano + cola acrílica para uso interior", 2];
$data[] = [1947, "Doming: vinilo espejo autoadhesivo + resina poliuretano + cola acrílica para uso exterior", 2];
$data[] = [1949, "270 gr. tela poliéster ignífuga M1", 2];
$data[] = [1951, "760µ PVC blanco", 2];
$data[] = [1952, "Termo selectivo dorado", 2];
$data[] = [1953, "Termo selectivo plateado", 2];
$data[] = [1954, "Sin", 2];
$data[] = [1955, "LO-CO", 2];
$data[] = [1956, "HI-CO 2750", 2];
$data[] = [1957, "Textura tela 1 cara", 2];
$data[] = [1958, "Sobre el recto", 2];
$data[] = [1959, "Sobre el dorso", 2];
$data[] = [1960, "Perlado nacarado 1 cara", 2];
$data[] = [1961, "760µ PVC trasparente", 2];
$data[] = [1962, "Perlado nacarado 2 caras", 2];
$data[] = [1963, "Campo texto y/o código de barras y/o numeración 1 cara", 2];
$data[] = [1964, "Campo texto y/o código de barras y/o numeración dorso", 2];
$data[] = [1965, "PVC + 1 acabado", 2];
$data[] = [1967, "Sin", 2];
$data[] = [1968, "Con", 2];

// italien 5

$data[] = [1292, "Arricchito", 5];
$data[] = [1298, "In nylon flessibile", 5];
$data[] = [1299, "Contenitore con rotelle", 5];
$data[] = [1324, "Asole agli angoli", 5];
$data[] = [1354, "135g patinata lucida + cartoncino compatto 20/10", 5];
$data[] = [1357, "135g incapsulato lucido ", 5];
$data[] = [1384, "60µ adesivo antistrappo incollato su cartoncino compatto", 5];
$data[] = [1434, "UK 120gsm Premium Smooth", 5];
$data[] = [1436, "Bacchette a pressione in alto e in basso", 5];
$data[] = [1445, "172µ: adesivo Aslan traslucido M1 62µ + supporto 110µ", 5];
$data[] = [1446, "172µ: adesivo Aslan fluo rosso 62µ + supporto 110µ", 5];
$data[] = [1447, "172µ: adesivo Aslan effetto sabbiato 62µ + supporto 110µ", 5];
$data[] = [1487, "UK 135gsm Trisolv postart paper", 5];
$data[] = [1492, "UK 234gsm Polyester Lightblock", 5];
$data[] = [1529, "172µ: adesivo Aslan fluo giallo 62µ + supporto 110µ", 5];
$data[] = [1535, "75g carta usomano bianca", 5];
$data[] = [1536, "Multi-pieghe", 5];
$data[] = [1537, "UK 430gsm Polyester Lightblock", 5];
$data[] = [1573, "Cartonato", 5];
$data[] = [1574, "UK 400gsm Silk Coated", 5];
$data[] = [1844, "Bronzo", 5];
$data[] = [1845, "Printbox", 5];
$data[] = [1847, "UV lucida totale ", 5];
$data[] = [1853, "3ª richiesta", 5];
$data[] = [1884, "UK 100gsm Premium Smooth", 5];
$data[] = [1904, "170g per plastificazione", 5];
$data[] = [1905, "Taurina, Cola, Mela, Limone, Arancia, Vivifruits, Thé verde, Uva", 5];
$data[] = [1914, "Blu", 5];
$data[] = [1915, "Senza", 5];
$data[] = [1916, "Con picto P", 5];
$data[] = [1917, "260g semi-opaca Prova colore Fogra", 5];
$data[] = [1918, "1 giorno", 5];
$data[] = [1929, "Pompa 12V", 5];
$data[] = [1930, "Sacca di trasporto", 5];
$data[] = [1931, "Senza", 5];
$data[] = [1932, "1 poltrona + 1 tavolino basso", 5];
$data[] = [1933, "2 poltrone + 1 tavolino basso", 5];
$data[] = [1934, "2 pouf + 1 tavolino basso", 5];
$data[] = [1935, "1 sdraio + 1 tavolino basso", 5];
$data[] = [1936, "Struttura gonfiabile", 5];
$data[] = [1938, "Senza", 5];
$data[] = [1939, "Da 1 a 4 angoli arrotondati", 5];
$data[] = [1949, "270g tessuto poliestere ignifugo m1", 5];
$data[] = [1951, "760µ PVC bianco", 5];
$data[] = [1952, "Lamina a caldo", 5];
$data[] = [1953, "Lamina argento", 5];
$data[] = [1954, "Senza", 5];
$data[] = [1955, "LO-CO", 5];
$data[] = [1956, "HI-CO 2750", 5];
$data[] = [1957, "Effetto tessuto fronte", 5];
$data[] = [1958, "Sul fronte", 5];
$data[] = [1959, "Sul retro", 5];
$data[] = [1960, "Effetto perlato fronte", 5];
$data[] = [1961, "760µ PVC trasparente", 5];
$data[] = [1962, "Effetto perlato fronte/retro", 5];
$data[] = [1963, "Dati variabili testi e/o codice a barre e/o numerazione fronte", 5];
$data[] = [1964, "Dati variabili testi e/o codice a barre e/o numerazione retro", 5];
$data[] = [1965, "PVC + 1 finitura", 5];
$data[] = [1967, "Senza", 5];
$data[] = [1968, "Con", 5];

// portugal 6

$data[] = [1753, "Caixa garrafa com janela L", 6];
$data[] = [1754, "Caixa garrafa sem janela S", 6];
$data[] = [1755, "Caixa garrafa sem janela M", 6];
$data[] = [1756, "Caixa garrafa sem janela L", 6];
$data[] = [1757, "Caixa envio S", 6];
$data[] = [1758, "Caixa envio M", 6];
$data[] = [1759, "Caixa envio L", 6];
$data[] = [1760, "Caixa envio quadrada S", 6];
$data[] = [1761, "Caixa envio quadrada M", 6];
$data[] = [1762, "Caixa envio quadrada L", 6];
$data[] = [1763, "Caixa envio retangular S", 6];
$data[] = [1764, "Caixa envio retangular M", 6];
$data[] = [1765, "Caixa enivo retangular L", 6];
$data[] = [1766, "Caixa chocolate S", 6];
$data[] = [1767, "Caixa chocolate M", 6];
$data[] = [1768, "Caixa chocolate L", 6];
$data[] = [1769, "Lunch Box S", 6];
$data[] = [1770, "Lunch Box M", 6];
$data[] = [1771, "Lunch Box L", 6];
$data[] = [1772, "Caixa produto baixa S", 6];
$data[] = [1773, "Caixa produto baixa M", 6];
$data[] = [1774, "Caixa produto baixa L", 6];
$data[] = [1776, "Caixa produto alta M", 6];
$data[] = [1777, "Caixa produto alta L", 6];
$data[] = [1778, "Caixa expositor S", 6];
$data[] = [1779, "Caixa expositor M", 6];
$data[] = [1780, "Caixa expositor L", 6];
$data[] = [1783, "240 gr. papel cor azul", 6];
$data[] = [1784, "240 gr. papel cor fúcsia", 6];
$data[] = [1785, "240 gr. papel cor preto", 6];
$data[] = [1786, "240 gr. papel cor vermelho", 6];
$data[] = [1787, "240 gr. papel cor turquesa", 6];
$data[] = [1788, "240 gr. papel cor verde", 6];
$data[] = [1789, "250 gr. Chromolux preto", 6];
$data[] = [1790, "250 gr. Chromolux vermelho", 6];
$data[] = [1795, "336 gr. papel cartão microcanal kraft marrom", 6];
$data[] = [1796, "275 gr. cartão gráfico branco couché 1 face", 6];
$data[] = [1812, "Pré-colado", 6];
$data[] = [1843, "Marcador branco", 6];
$data[] = [1847, "Total UV brilhante", 6];
$data[] = [1853, "3ª encomenda", 6];
$data[] = [1854, "240g papel cor violeta", 6];
$data[] = [1855, "240g papel cor ouro viejo", 6];
$data[] = [1856, "240g papel cor laranja", 6];
$data[] = [1857, "240g papel cor cinza", 6];
$data[] = [1862, "500g quadro Expolit M2", 6];
$data[] = [1865, "1800g papel couché branco brilhante 1 face contracolada sobre cartão alveolar kraft marrom", 6];
$data[] = [1866, "2200g papel couché branco brilhante 1 face contracolada sobre cartão alveolar branco", 6];
$data[] = [1867, "1200g papel couché branco brilhante 1 face contracolado sobre cartão alveolar kraft marrom", 6];
$data[] = [1868, "900g cartão branco contracolado sobre cartão kraft triplo microcanelado", 6];
$data[] = [1869, "680g cartão branco contracolado sobre cartão kraft triplo microcanelado", 6];
$data[] = [1870, "350g cartão gráfico branco couché 1 face", 6];
$data[] = [1871, "Módulo alto", 6];
$data[] = [1872, "Módulo central", 6];
$data[] = [1873, "Módulo baixo", 6];
$data[] = [1874, "Recarga", 6];
$data[] = [1875, "Completo", 6];
$data[] = [1877, "135g couché brilhante HP", 6];
$data[] = [1878, "170g couché brilhante HP", 6];
$data[] = [1879, "170g couché mate HP", 6];
$data[] = [1884, "UK 100gsm Premium Smooth", 6];
$data[] = [1893, "Musgo dos bosques e líquen de renas", 6];
$data[] = [1895, "Negro + 1 cor", 6];
$data[] = [1896, "Sem", 6];
$data[] = [1897, "Termorelevo", 6];
$data[] = [1898, "300g cartão gráfico couché 1 face", 6];
$data[] = [1905, "Taurina, Cola, Maçã, Limão, Laranja, Multifrutas, Chá Verde, Uva", 6];
$data[] = [1919, "Forex 3mm", 6];
$data[] = [1920, "1100g cartão compacto branco", 6];
$data[] = [1921, "240g papel cor azul, fúcsia, violeta, vermelho, preto, botão de ouro, cinza", 6];
$data[] = [1922, "120g papel cor fúcsia", 6];
$data[] = [1923, "120g papel cor botão de ouro", 6];
$data[] = [1924, "120g papel cor cinza", 6];
$data[] = [1925, "120g papel cor preto", 6];
$data[] = [1926, "120g papel cor vermelho", 6];
$data[] = [1927, "120g papel cor violeta", 6];
$data[] = [1928, "185g tecido poliéster", 6];
$data[] = [1929, "Bomba 12V", 6];
$data[] = [1930, "Saco de transporte", 6];
$data[] = [1931, "Sem", 6];
$data[] = [1932, "1 poltrona + 1 mesa baixa", 6];
$data[] = [1933, "2 poltronas + 1 mesa baixa", 6];
$data[] = [1934, "2 pufs + 1 mesa baixa", 6];
$data[] = [1935, "1 espreguiçadeira + 1 mesa baixa", 6];
$data[] = [1936, "Estrutura inflável", 6];
$data[] = [1937, "Brilhante", 6];
$data[] = [1938, "Sem", 6];
$data[] = [1939, "De 1 a 4 cantos redondos", 6];
$data[] = [1940, "Sem", 6];
$data[] = [1941, "Ouro quente", 6];
$data[] = [1942, "Prata quente", 6];
$data[] = [1943, "1 perfuração de 4mm lado do agrafado", 6];
$data[] = [1944, "1 perfuração de 4mm lado oposto ao agrafado", 6];
$data[] = [1945, "Gota de resina: autocolante + resina poliuretano bicomposta + cola termoselável", 6];
$data[] = [1946, "Gota de resina: autocolante espelhado + resina poliuretano + cola aquosa para uso interior", 6];
$data[] = [1947, "Gota de resina: autocolante espelhado + resina poliuretano + cola acrílica para uso exterior", 6];
$data[] = [1949, "270g tecido poliéster ignífugo M1", 6];
$data[] = [1950, "PETG transparente 2mm", 6];
$data[] = [1951, "760µ PVC branco", 6];
$data[] = [1952, "Termolocalizado dourado", 6];
$data[] = [1953, "Termolocalizado prateado", 6];
$data[] = [1954, "Sem", 6];
$data[] = [1955, "LO-CO", 6];
$data[] = [1956, "HI-CO 2750", 6];
$data[] = [1957, "Textura tecido 1 face", 6];
$data[] = [1958, "Sobre a frente", 6];
$data[] = [1959, "Sobre o verso", 6];
$data[] = [1960, "Perolado nacarado 1 face", 6];
$data[] = [1961, "760µ PVC trasparente", 6];
$data[] = [1962, "Perolado nacarado 2 faces", 6];
$data[] = [1963, "Campo texto e/ou código de barras e/ou numeração frente", 6];
$data[] = [1964, "Campo texto e/ou código de barras e/ou numeração verso", 6];
$data[] = [1965, "PVC + 1 acabamento", 6];
$data[] = [1967, "Sem", 6];
$data[] = [1968, "Com", 6];

try {

foreach ($data as $d) {
    $IDValeurOption = $d[0];
    $libelle        = $d[1];
    $IDLangue       = $d[2];

    $select = "SELECT * FROM TBL_PRODUIT_OPTION_VALEUR_TRAD WHERE IDProduitOptionValeur=$IDValeurOption AND IDLangue=$IDLangue";
    $stmt   = DB::get()->query($select);

    if ($dto = $stmt->fetch()) {
        // UPDATE
        echo "$IDValeurOption - $IDLangue : Found!"."\n";
        //$q = "UPDATE TBL_PRODUIT_OPTION_VALEUR_TRAD SET LibelleTraduit='$libelle' WHERE IDProduitOptionValeur=$IDValeurOption AND IDLangue=$IDLangue";
    } else {
        //INSERT
        echo "$IDValeurOption - $IDLangue : NOT f!"."\n";
        //$q = "INSERT INTO TBL_PRODUIT_OPTION_VALEUR_TRAD(LibelleTraduit, IDLangue, IDProduitOptionValeur) VALUES('$libelle', $IDLangue, $IDValeurOption)";
    }

    /*if ($stmt = DB::get()->query($q)) {
        echo "DONE"."\n";;
    } else {
        var_dump(DB::get()->errorInfo());
        var_dump($stmt);
    }*/


}

} catch (\RBM\SqlQuery\Exception $e) {
    var_dump($e);
}