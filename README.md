# Verificador de Posição - iCarros

Um script em PHP que verifica a posição dos anúncios cadastrados na plataforma iCarros, e que retorna posição atual e valor do primeiro colocado.

*Este é um projeto pessoal feito para agilizar tarefas do meu dia a dia, sem maiores intenções. Por utilizar diariamente, adicionei no Git para testar meus novos conhecimentos, e ir aprimorando o código conforme a minha evolução e necessidade.*

### Utilização
1. Adicione o script à um servidor PHP.
2. No arquivo CSV, adicione na coluna LINKS as URLS das páginas onde o script deverá realizar a busca. Esta página deve ser a de busca já filtrada, exemplo:
```
https://www.icarros.com.br/ache/listaanuncios.jsp?bid=0&opcaocidade=1&foa=1&anunciosNovos=1&anunciosUsados=1&marca1=31&modelo1=919&anomodeloinicial=2014&anomodelofinal=2014&precominimo=0&precomaximo=0&cidadeaberto=&escopo=2&locationSop=cid_7043.1_-est_RJ.1_-esc_2.1_-rai_50.1_
```
3. Inicie o index.php pelo servidor e deixe o script trabalhar!