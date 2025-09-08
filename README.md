# CnpjCalculator

Uma biblioteca em **C#** para validação de **CNPJs tradicionais (numéricos)** e **CNPJs alfanuméricos** (padrão SERPRO).

---

## Funcionalidades

- Valida **CNPJ clássico** (somente números)  
- Valida **CNPJ alfanumérico** (letras + números, ex: `E9.640.DP2/MQ95-33`)  
- Aceita CNPJs **com ou sem máscara**  
- Detecta automaticamente se o CNPJ é numérico ou alfanumérico  
- Cálculo dos dígitos verificadores (DV) conforme especificação oficial (módulo 11)

---

## Uso

### 1. Importar a classe
Coloque a classe `CnpjCalculator.cs` no seu projeto. Veja os testes efetuados

### 2. Validar CNPJs

```csharp
using System;

class Program
{
    static void Main()
    {
        // CNPJ tradicional
        string cnpjNumerico = "11.444.777/0001-61";
        Console.WriteLine(CnpjCalculator.ValidarCNPJ(cnpjNumerico)); // true

        // CNPJ alfanumérico
        string cnpjAlfa = "E9.640.DP2/MQ95-33";
        Console.WriteLine(CnpjCalculator.ValidarCNPJ(cnpjAlfa)); // true

        // CNPJ inválido
        string cnpjInvalido = "11.444.777/0001-00";
        Console.WriteLine(CnpjCalculator.ValidarCNPJ(cnpjInvalido)); // false
    }
}
