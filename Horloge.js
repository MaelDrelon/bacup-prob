
//variable qui permet d'identifier le timer afin de pouvoir l'arrêter
var timerID = null;
var timerActif = false;

if(document.images)
{
  chiffre = new Array(10);
  chiffre[0] = new Image(); chiffre[0].src = "images/dark green/dg0.gif";
  chiffre[1] = new Image(); chiffre[1].src = "images/dark green/dg1.gif";
  chiffre[2] = new Image(); chiffre[2].src = "images/dark green/dg2.gif";
  chiffre[3] = new Image(); chiffre[3].src = "images/dark green/dg3.gif";
  chiffre[4] = new Image(); chiffre[4].src = "images/dark green/dg4.gif";
  chiffre[5] = new Image(); chiffre[5].src = "images/dark green/dg5.gif";
  chiffre[6] = new Image(); chiffre[6].src = "images/dark green/dg6.gif";
  chiffre[7] = new Image(); chiffre[7].src = "images/dark green/dg7.gif";
  chiffre[8] = new Image(); chiffre[8].src = "images/dark green/dg8.gif";
  chiffre[9] = new Image(); chiffre[9].src = "images/dark green/dg9.gif";
  Blanc = new Image(); Blanc.src = "images/dark green/space.gif";
}
function Arréter() 
{
  if (timerActif) clearTimeout(timerID);
  timerActif = false;
}

function Démarrer() 
{
    Arréter();
    AfficheHeure();
}

function AfficheHeure() 
{
  var now = new Date();
  var hour = now.getHours();
  var min = now.getMinutes();
  var sec = now.getSeconds();

  affiche(hour,0);
  affiche(min,3);
  affiche(sec,6);
  timerID = setTimeout("AfficheHeure()",1000);
  timerActif = true;
}

function affiche(nombre, rang) 
{
  var unites = nombre % 10
  var dizaines = Math.floor(nombre / 10)
  document.images[rang+1].src = chiffre[unites].src;
  if (dizaines == 0 && rang == 0)
    document.images[rang].src = Blanc.src;
  else
    document.images[rang].src = chiffre[dizaines].src ;
}
