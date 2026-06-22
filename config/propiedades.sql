-- ============================================================
-- TABLAS PARA PROPIEDADES E IMÁGENES - PNK Inmobiliaria
-- Agregar al script SQL existente de la base de datos
-- ============================================================

-- Tabla de propiedades
CREATE TABLE IF NOT EXISTS propiedades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,                  -- FK al propietario (tabla usuarios)
    tipo_propiedad ENUM('Casa','Departamento','Terreno') NOT NULL,
    descripcion TEXT NOT NULL,
    banos INT DEFAULT 0,
    dormitorios INT DEFAULT 0,
    area_terreno DECIMAL(10,2) DEFAULT 0,     -- m²
    area_construida DECIMAL(10,2) DEFAULT 0,  -- m²
    precio_pesos BIGINT DEFAULT 0,
    precio_uf DECIMAL(10,2) DEFAULT 0,
    region VARCHAR(100) NOT NULL,
    provincia VARCHAR(100) NOT NULL,
    comuna VARCHAR(100) NOT NULL,
    sector VARCHAR(150) DEFAULT '',
    bodega TINYINT(1) DEFAULT 0,
    estacionamiento TINYINT(1) DEFAULT 0,
    logia TINYINT(1) DEFAULT 0,
    cocina_amoblada TINYINT(1) DEFAULT 0,
    antejardin TINYINT(1) DEFAULT 0,
    patio_trasero TINYINT(1) DEFAULT 0,
    piscina TINYINT(1) DEFAULT 0,
    solicitar_visita TINYINT(1) DEFAULT 0,
    fecha_publicacion DATE NOT NULL,
    estado ENUM('activa','inactiva','vendida') DEFAULT 'activa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de fotografías de propiedades
CREATE TABLE IF NOT EXISTS fotos_propiedades (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_propiedad INT NOT NULL,
    nombre_archivo VARCHAR(255) NOT NULL,
    es_principal TINYINT(1) DEFAULT 0,
    estado TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_propiedad) REFERENCES propiedades(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Índices para búsquedas rápidas
CREATE INDEX idx_prop_tipo ON propiedades(tipo_propiedad);
CREATE INDEX idx_prop_region ON propiedades(region);
CREATE INDEX idx_prop_comuna ON propiedades(comuna);
CREATE INDEX idx_prop_estado ON propiedades(estado);
CREATE INDEX idx_prop_usuario ON propiedades(id_usuario);
