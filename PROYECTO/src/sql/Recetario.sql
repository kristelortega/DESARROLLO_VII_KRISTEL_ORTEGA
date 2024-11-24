-- Tabla actualizada de usuarios
CREATE TABLE usuarios (
                          id VARCHAR(255) PRIMARY KEY UNIQUE, -- ID único proporcionado por Google
                          email VARCHAR(255) NOT NULL UNIQUE, -- Email único del usuario
                          nombre VARCHAR(255), -- Nombre del usuario
                          fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Fecha de registro
);

-- Tabla de recetas
CREATE TABLE recetas (
                         id INT AUTO_INCREMENT PRIMARY KEY,
                         usuario_id VARCHAR(255) NOT NULL, -- Referencia al ID de Google del usuario
                         titulo VARCHAR(255) NOT NULL,
                         descripcion TEXT,
                         tiempo_preparacion INT,
                         imagen VARCHAR(255),
                         creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                         FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabla de ingredientes
CREATE TABLE ingredientes (
                              id INT AUTO_INCREMENT PRIMARY KEY,
                              receta_id INT NOT NULL,
                              nombre VARCHAR(255) NOT NULL,
                              cantidad VARCHAR(50),
                              FOREIGN KEY (receta_id) REFERENCES recetas(id) ON DELETE CASCADE
);

CREATE TABLE comentarios (
                             id INT AUTO_INCREMENT PRIMARY KEY,
                             receta_id INT NULL,
                             usuario_id VARCHAR(255) NOT NULL,
                             comentario TEXT NOT NULL,
                             calificacion INT CHECK (calificacion BETWEEN 1 AND 5),
                             creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                             receta_url VARCHAR(255),  -- Nueva columna para la URL de la receta
                             FOREIGN KEY (receta_id) REFERENCES recetas(id) ON DELETE CASCADE,
                             FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
                             CONSTRAINT check_receta CHECK (
                                 (receta_id IS NOT NULL AND receta_url IS NULL) OR
                                 (receta_id IS NULL AND receta_url IS NOT NULL)
                                 )  -- Restricción para asegurar que solo se use receta_id o receta_url
);


