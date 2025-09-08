# CnpjCalculator

Uma biblioteca em **PHP** para validação de **CNPJs tradicionais (numéricos)** e **CNPJs alfanuméricos** (padrão SERPRO).

---

## Funcionalidades

- Valida **CNPJ clássico** (somente números)  
- Valida **CNPJ alfanumérico** (letras + números, ex: `E9.640.DP2/MQ95-33`)  
- Aceita CNPJs **com ou sem máscara**  
- Detecta automaticamente se o CNPJ é numérico ou alfanumérico  
- Cálculo dos dígitos verificadores (DV) conforme especificação oficial (módulo 11)

---

## Uso

```php
var_dump(CnpjCalculator::validarCNPJ("11.444.777/0001-61")); // true
var_dump(CnpjCalculator::validarCNPJ("E9.640.DP2/MQ95-33")); // true