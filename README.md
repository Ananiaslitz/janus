## Instalação

Após instalar o pacote via Composer, execute o seguinte comando para mesclar as configurações de dependências do pacote com as da sua aplicação Hyperf:

```bash
php bin/merge-config.php
```

**Nota**: Certifique-se de fazer um backup do seu arquivo config/autoload/dependencies.php antes de executar o script de merge.

### Considerações

- **Backup**: 
- **Conflitos**: 
- **Validação**: 

Essa abordagem permite uma integração suave do seu pacote com as aplicações Hyperf, minimizando o risco de sobreposição indesejada de configurações e proporcionando uma experiência de usuário clara e segura.
