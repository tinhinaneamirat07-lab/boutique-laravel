# Télécharge les photos de la maquette dans public/maquettes/images (à lancer une fois)
$ids = @(11324518,11324547,1801279,26772103,26925248,27018339,27204280,27204295,27204296,27256450,27256456,27256473,27426618,31129841,35070982,35618111,38892551,5488660,9080069,9207813,9267585)
$dir = Join-Path $PSScriptRoot "images"
New-Item -ItemType Directory -Force -Path $dir | Out-Null
foreach ($id in $ids) {
    $out = Join-Path $dir "$id.jpg"
    if (Test-Path $out) { continue }
    $url = "https://images.pexels.com/photos/$id/pexels-photo-$id.jpeg?auto=compress&cs=tinysrgb&w=1400"
    try { Invoke-WebRequest -Uri $url -OutFile $out -UseBasicParsing; Write-Host "OK  $id" }
    catch { Write-Host "ERREUR $id" -ForegroundColor Red }
}
Write-Host "Termine : $((Get-ChildItem $dir).Count) photos dans $dir"
