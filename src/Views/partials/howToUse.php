<h2>Ejemplos</h2>
<div class="SnTab SnMb-5">
    <div class="SnTab-header">
        <div class="SnTab-title is-active">cURL</div>
        <div class="SnTab-title">Node.js</div>
        <div class="SnTab-title">.NET</div>
        <div class="SnTab-title">PHP</div>
    </div>
    <div class="SnTab-content">
<pre><code class="language-bash">
curl --location --request POST '<?= HOST . URL_PATH ?>/api/v1/ruc' \
--header 'Content-Type: application/json' \
--data-raw '{
    "ruc": "20788757225",
    "token": "YOUR_TOKEN"
}'
</code></pre>
    </div>
    <div class="SnTab-content">
<pre><code class="language-JavaScript">
var request = require('request');
var options = {
  'method': 'POST',
  'url': '<?= HOST . URL_PATH ?>/api/v1/ruc',
  'headers': {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({"ruc":"20788757225","token":"YOUR_TOKEN"})

};
request(options, function (error, response) {
  if (error) throw new Error(error);
  console.log(response.body);
});
</code></pre>
    </div>
    <div class="SnTab-content">
<pre><code class="language-aspnet">
require "uri"
require "net/http"

url = URI("<?= HOST . URL_PATH ?>/api/v1/ruc")

http = Net::HTTP.new(url.host, url.port);
request = Net::HTTP::Post.new(url)
request["Content-Type"] = "application/json"
request.body = "{\r\n    \"ruc\": \"20788757225\",\r\n    \"token\": \"YOUR_TOKEN\"\r\n}"

response = http.request(request)
puts response.read_body
</code></pre>
    </div>
    <div class="SnTab-content">
<pre><code class="language-php">
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => '<?= HOST . URL_PATH ?>/api/v1/ruc',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "ruc": "20788757225",
    "token": "YOUR_TOKEN"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;
</code></pre>
    </div>
</div>


<div class="SnCard SnMb-3">
    <div class="SnCard-header" data-collapsetrigger="lastUpdate">ACTUALIZACIÓN DIARIA</div>
    <div class="SnCard-body SnCollapse" data-collapse="lastUpdate">
        <p><?= HOST ?> se actualiza DIARIAMENTE con la información que optenemos desde el PADRÓN REDUCIDO que publica la SUNAT todos los días.</p>
        <p>Última actualización con la SUNAT hace cerca de 20 horas .</p>
    </div>
</div>

<div class="SnCard SnMb-3">
    <div class="SnCard-header" data-collapsetrigger="hoToUse">COMO FUNCIONA</div>
    <div class="SnCard-body SnCollapse" data-collapse="hoToUse">
        <ul>
            <li>Regístrate en <?= HOST ?> para optener una RUTA y generar un TOKEN.</li>
            <li>Te conectas con nuestra aplicación vía WEB SERVICE REST, enviando a la RUTA el TOKEN y un RUC en formato .JSON (Archivo PLANO)</li>
            <li>Las consultar a nuestra aplicación se hace vía POST.</li>
            <li>Recibirás una respuesta en .JSON que puedes leer y usar inmediatamente en tu aplicación.</li>
            <li>La RUTA a la que debes enviar las consultas es:<pre><code><?php echo HOST . URL_PATH ?>/api/v1?token=YOUR_TOKEN</code></pre></li>
        </ul>
    </div>
</div>

<div class="SnCard SnMb-3">
    <div class="SnCard-header" data-collapsetrigger="exampleQuery">EJEMPLOS DE ARCHIVOS de CONSULTA</div>
    <div class="SnCard-body SnCollapse" data-collapse="exampleQuery">
        <p>Envíanos un a la RUTA el TOKEN y un RUC.</p>
        <p>Ruta para la solicitud:</p>
        <pre><code class="language-bash"><?= HOST . URL_PATH ?>/api/v1/ruc</code></pre>
        <p>Tipo de solicitud:</p>
        <pre><code class="language-bash">POST</code></pre>
        <p>Cabecera de la solititud:</p>
        <pre><code class="language-bash">Content-Type: application/json</code></pre>
        <p>Cuerpo de la solititud:</p>
        <pre><code class="language-bash">{
    "token": "YOUR_TOKEN",
    "ruc": "10178520739"
}</code></pre>
    </div>
</div>
