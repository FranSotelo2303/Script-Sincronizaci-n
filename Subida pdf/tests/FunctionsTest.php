<?php
//Se agregan pruebas unitarias haciendo uso de Mockeo para simular la conexión de la BD.
//Probar con: vendor/bin/phpunit
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/functions.php';

class FunctionsTest extends TestCase
{
    public function testhandleFileUpload()
    {
        $file = [
            'name' => 'test.pdf',
            'type' => 'application/pdf',
            'size' => 123,
            'tmp_name' => 'path/to/tmp/file'
        ];
        $documentoId = '1234567890';
        $docType = 'Historia Clínica';

        $conn = $this->createMock(mysqli::class);
        $stmt = $this->createMock(mysqli_stmt::class);

        $conn->method('prepare')->willReturn($stmt);
        $stmt->method('execute')->willReturn(true);

        // Asegurar que bind_param y send_long_data retornen true
        $stmt->method('bind_param')->willReturn(true);
        $stmt->method('send_long_data')->willReturn(true);
        
        // Simular el contenido del archivo
        file_put_contents('path/to/tmp/file', 'dummy content');

        $result = handleFileUpload($conn, $file, $documentoId, $docType);
        $this->assertTrue($result['success']);
        $this->assertEquals('Archivo subido exitosamente', $result['message']);

        // Limpiar
        unlink('path/to/tmp/file');
    }
}
