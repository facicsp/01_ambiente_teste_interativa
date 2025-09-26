<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script type="text/javascript">
      function somar(){
        var n1 = $("input[name=n1]").val();
        var n2 = $("input[name=n2]").val();
        $.ajax({
          type: 'POST',
          data: {
            'n1': n1,
            'n2': n2
          },
          url : 'soma.php',
          success: function(data) {
            //alert(data);
            document.getElementById("resultado").innerHTML = "<p>"+data+"</p>";
          }
        });
      }
    </script>
  </head>
  <body>
    <input type="text" name="n1" placeholder="Digite um Número"><br><br>
    <input type="text" name="n2" placeholder="Digite um Número"><br><br>
    <button onclick="somar()">Enviar</button>
    <div id="resultado"></div>
  </body>
</html>
