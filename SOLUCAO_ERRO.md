# 🔧 Solução para o Erro "Erro ao salvar desafio"

## 📋 Problema Identificado

O erro que você está vendo acontece porque:

1. **Servidor não está rodando**: A aplicação precisa de um servidor web para funcionar
2. **Banco de dados não configurado**: O MySQL pode não estar instalado ou rodando
3. **Configuração de CORS**: Problemas de permissão entre frontend e backend

## 🚀 Soluções Disponíveis

### Opção 1: Usar a Versão Simplificada (RECOMENDADA)

Criei uma versão que funciona **sem banco de dados**, usando arquivos JSON:

1. **Inicie o servidor PHP**:
   ```bash
   php -S localhost:8000
   ```

2. **Acesse a aplicação**:
   ```
   http://localhost:8000
   ```

3. **Teste a funcionalidade**:
   - Adicione um novo desafio
   - Conclua desafios
   - Veja o ranking

### Opção 2: Configurar Banco de Dados Completo

Se você quiser usar a versão completa com MySQL:

1. **Instale XAMPP ou Laragon** (inclui MySQL)
2. **Inicie o MySQL**
3. **Execute o script de configuração**:
   ```
   http://localhost:8000/setup.php
   ```

## 📁 Arquivos Criados

- `setup.php` - Script de configuração e verificação
- `api/config_simple.php` - Configuração sem banco de dados
- `api/desafios_simple.php` - API de desafios simplificada
- `api/ranking_simple.php` - API de ranking simplificada
- `api/concluir_desafio_simple.php` - API de conclusão simplificada
- `data/` - Pasta com arquivos JSON (criada automaticamente)

## 🔍 Como Testar

1. **Abra o terminal** na pasta do projeto
2. **Execute**: `php -S localhost:8000`
3. **Abra o navegador** em `http://localhost:8000`
4. **Teste criar um desafio**:
   - Título: "Meu Primeiro Desafio"
   - Área: "Matemática"
   - Pontos: 10
   - Descrição: "Resolver 5 exercícios de álgebra"

## ✅ Resultado Esperado

- ✅ Desafio salvo com sucesso
- ✅ Desafio aparece na lista
- ✅ Pode ser editado e deletado
- ✅ Pode ser concluído
- ✅ Ranking atualizado

## 🆘 Se Ainda Houver Problemas

1. **Verifique se o PHP está instalado**:
   ```bash
   php --version
   ```

2. **Teste o script de configuração**:
   ```
   http://localhost:8000/setup.php
   ```

3. **Verifique os logs de erro** no terminal onde o servidor está rodando

## 📞 Suporte

Se ainda tiver problemas, verifique:
- Versão do PHP (recomendado 7.4+)
- Permissões de escrita na pasta `data/`
- Firewall bloqueando a porta 8000
