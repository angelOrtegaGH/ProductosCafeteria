create DATABASE cafeteria;

create table categorias(
    id int AUTO_INCREMENT primary key ,
    nombre varchar (30) not null,
    descripcion varchar (100)
);

create table productos(
	id int AUTO_INCREMENT primary key,
	nombre varchar(70) not null ,
	referencia varchar (30) unique not null,
	precio int not null,
	peso int not null,
	id_categoria int,
	stock int not null DEFAULT 0,
	fecha_creacion date not null,
	foreign key (id_categoria)
        references categorias(id) on delete restrict on update cascade
);

insert into categorias(nombre, descripcion) values ('LÁCTEOS', 'Descripción categoria de producto #1'),
                                                    ('BOTANAS', 'Descripción categoria de producto #2'),
                                                    ('CONFITERÍA/DULCERIA', 'Descripción categoria de producto #3'),
                                                    ('HARINAS Y PAN', 'Descripción categoria de producto #4'),
                                                    ('FRUTAS Y VERDURAS', 'Descripción categoria de producto #5');