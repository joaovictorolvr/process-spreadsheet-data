import os
import jpype
import asposecells
import sys

# Inicializa a JVM
jpype.startJVM()

from asposecells.api import Workbook

def convert_numbers_to_xlsx(input_file, output_file):
    # Verifica se o arquivo de entrada existe
    if not os.path.isfile(input_file):
        print(f'Arquivo de entrada não encontrado: {input_file}')
        sys.exit(1)
    
    # Carrega o arquivo .numbers e converte para .xlsx
    workbook = Workbook(input_file)
    workbook.save(output_file)
    
    print(f'Arquivo convertido: {input_file} -> {output_file}')

if __name__ == '__main__':
    if len(sys.argv) != 3:
        print('Uso: python script.py <arquivo_entrada.numbers> <arquivo_saida.xlsx>')
        sys.exit(1)
    
    input_file = sys.argv[1]
    output_file = sys.argv[2]
    
    convert_numbers_to_xlsx(input_file, output_file)
    
    # Finaliza a JVM
    jpype.shutdownJVM()
