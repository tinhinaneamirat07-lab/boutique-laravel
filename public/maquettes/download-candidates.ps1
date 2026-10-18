# Telecharge des photos candidates (produits seuls) pour les verifier avant de les utiliser
$ids = @(10528003,9080069,27204296,18935118,34341694,27204295,13035176,27204280,26772103,1801279,23692992,31129829,31129841,27174537,9267585,31451024,26925248,26925256,38892551,8079829,30313904,30128844,31507738,11324518,4219176,5488660,35618111,21419626,11324547,27289121,15475641,29342147)
$dir = Join-Path $PSScriptRoot "images"
New-Item -ItemType Directory -Force -Path $dir | Out-Null
foreach ($id in $ids) {
    $out = Join-Path $dir "$id.jpg"
    if (Test-Path $out) { continue }
    $url = "https://images.pexels.com/photos/$id/pexels-photo-$id.jpeg?auto=compress&cs=tinysrgb&w=1400"
    try { Invoke-WebRequest -Uri $url -OutFile $out -UseBasicParsing; Write-Host "OK  $id" }
    catch { Write-Host "ERREUR $id" -ForegroundColor Red }
}
Write-Host "Termine"
