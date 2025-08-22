# ✅ Checklist - Desafio Técnico em Laravel (Integração com Fake Store API)

## Entregas obrigatórias
- [x] **Middleware de integração**
    - [x] Validar presença de um header personalizado (ex.: `X-Client-Id`)
    - [x] Logar entrada e saída das requisições (rota, status) e medir tempo de resposta
    - [x] Retornar erro `400` caso header esteja ausente

- [x] **Sincronização de produtos**
    - [x] Criar endpoint para iniciar a sincronização com a Fake Store API (`/integracoes/fakestore/sync`)
    - [x] Importar produtos e categorias
    - [x] Evitar duplicidades usando identificador externo único (`external_id = id da Fake Store`)
    - [x] Atualizar registros existentes quando houver mudanças

- [x] **Catálogo**
    - [x] Listar produtos com paginação
    - [x] Adicionar filtros: categoria, preço mínimo, preço máximo, busca por texto no título
    - [x] Implementar ordenação por preço (asc/desc)
    - [x] Buscar produto por id interno

- [x] **Estatísticas (SQL puro em pelo menos uma consulta)**
    - [x] Endpoint que retorna:
        - [x] Total de produtos
        - [x] Total por categoria
        - [x] Preço médio geral
        - [x] Top 5 produtos mais caros
    - [x] Usar SQL puro em pelo menos uma agregação

- [ ] **Resiliência e erros**
    - [ ] Tratar erro e timeout da API externa com resposta apropriada (`4xx/5xx`)
    - [x] Garantir que sincronização não quebre por causa de um item com erro (pular e registrar)

---

## Requisitos técnicos
- [x] Usar **Laravel 10+**
- [x] Banco de dados: MySQL ou PostgreSQL (documentar no README)
- [x] Criar migrations para produtos e categorias (relação 1:N)
- [x] Índices:
    - [x] `unique` em `external_id` de produtos
    - [x] Índice em nome da categoria
    - [x] Índices para filtros de listagem (`categoria_id`, preço)
- [x] Consumir API externa usando **HTTP Client nativo do Laravel**
- [x] Evitar problema de **N+1** queries na listagem
- [x] Cache de listagem/estatísticas com invalidação após sincronização
- [ ] Rate limiting por cliente no middleware
- [x] Paginação configurável via query string
- [ ] Retries com backoff no consumo da API externa
- [x] Testes (unitários/feature) para pelo menos um endpoint crítico
- [x] Docker (Dockerfile e docker-compose)
- [x] Logs estruturados (json) e correlação de request id

---

## Entrega (README)
- [ ] Instruções de setup
- [ ] Variáveis de ambiente
- [ ] Como rodar migrações
- [ ] Como iniciar o servidor
- [ ] Como executar a sincronização
- [ ] Como testar os endpoints
- [ ] Descrever decisão de modelagem e índices criados

---

## Critérios de avaliação
- [ ] Clareza do README e facilidade de execução
- [ ] Corretude funcional dos endpoints e middleware
- [ ] Qualidade da modelagem e uso de índices
- [ ] Boas práticas de Laravel e organização do código
- [ ] Performance básica (paginação, evitar N+1) e tratamento de erros
- [ ] Uso de SQL puro no endpoint de estatísticas
- [ ] Link para repositório público com código e README
