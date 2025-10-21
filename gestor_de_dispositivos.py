from abc import ABC, abstractmethod

class Dispositivo(ABC):
    def __init__(self, marca, modelo, precio, encendido=False):
        self._marca = marca
        self._modelo = modelo
        self._precio = precio
        self._encendido = encendido

    @property
    def precio(self):
        return self._precio

    @precio.setter
    def precio(self, valor):
        if valor < 0:
            print(f"{valor} es un valor inválido, el precio no puede ser negativo")
        else:
            self._precio = valor

    @abstractmethod
    def encender(self):
        pass

    @abstractmethod
    def apagar(self):
        pass

    def mostrar_info(self):
        print(f"Marca: {self._marca}\nModelo: {self._modelo}\nPrecio: {self._precio}€")


class Telefonos(Dispositivo):
    def __init__(self, marca, modelo, precio, numero_sim, capacidad_bateria, encendido=False):
        super().__init__(marca, modelo, precio, encendido)
        self._numero_sim = numero_sim
        self._capacidad_bateria = capacidad_bateria

    @property
    def capacidad_bateria(self):
        return self._capacidad_bateria

    @capacidad_bateria.setter
    def capacidad_bateria(self, cantidad):
        if 1 <= cantidad <= 100:
            self._capacidad_bateria = cantidad
        else:
            print("La capacidad debe estar entre 1 y 100%.")

    def mostrar_info(self):
        print(f"Marca: {self._marca}\nModelo: {self._modelo}\nPrecio: {self._precio}€\nSIM: {self._numero_sim}\nBatería: {self._capacidad_bateria}%")

    def encender(self):
        self._encendido = True
        print("El teléfono se encendió correctamente.")

    def apagar(self):
        self._encendido = False
        print("El teléfono se apagó correctamente.")


class Portatil(Dispositivo):
    def __init__(self, marca, modelo, precio, tamaño_pantalla, memoria_ram, encendido=False):
        super().__init__(marca, modelo, precio, encendido)
        self._tamaño_pantalla = tamaño_pantalla
        self._memoria_ram = memoria_ram

    @property
    def tamaño_pantalla(self):
        return self._tamaño_pantalla

    @tamaño_pantalla.setter
    def tamaño_pantalla(self, valor):
        if 10 <= valor < 19:
            self._tamaño_pantalla = valor
        else:
            print("El tamaño de pantalla debe estar entre 10 y 18 pulgadas.")

    def mostrar_info(self):
        print(f"Marca: {self._marca}\nModelo: {self._modelo}\nPrecio: {self._precio}€\nPantalla: {self._tamaño_pantalla}\"\nRAM: {self._memoria_ram}GB")

    def encender(self):
        self._encendido = True
        print("El portátil se encendió correctamente.")

    def apagar(self):
        self._encendido = False
        print("El portátil se apagó correctamente.")


def principal():
    lista_dispositivos = []

    def Agregar_telefono():
        marca = input("Marca del teléfono: ")
        modelo = input("Modelo: ")
        try:
            precio = float(input("Precio: "))
            numero_sim = int(input("Número de SIM: "))
            capacidad_bateria = int(input("Capacidad de batería (%): "))
        except:
            print("Valor inválido.")
            return
        telefono = Telefonos(marca, modelo, precio, numero_sim, capacidad_bateria)
        lista_dispositivos.append(telefono)

    def Agregar_portatil():
        marca = input("Marca del portátil: ")
        modelo = input("Modelo: ")
        try:
            precio = float(input("Precio: "))
            tamaño_pantalla = int(input("Tamaño de pantalla (pulgadas): "))
            memoria_ram = int(input("Memoria RAM (GB): "))
        except:
            print("Valor inválido.")
            return
        portatil = Portatil(marca, modelo, precio, tamaño_pantalla, memoria_ram)
        lista_dispositivos.append(portatil)

    def Mostrar_dispositivos():
        if not lista_dispositivos:
            print("No hay dispositivos.")
        else:
            for i, d in enumerate(lista_dispositivos, 1):
                print(f"\n[{i}]")
                d.mostrar_info()

    def Eliminar():
        try:
            indice = int(input("Índice a eliminar: ")) - 1
            if 0 <= indice < len(lista_dispositivos):
                eliminado = lista_dispositivos.pop(indice)
                print(f"'{eliminado._modelo}' eliminado correctamente.")
            else:
                print("Índice fuera de rango.")
        except:
            print("Entrada inválida.")

    def Encender_dispositivo():
        if not lista_dispositivos:
            print("No hay dispositivos para encender.")
            return
        indice = int(input("Índice a encender: ")) - 1
        if 0 <= indice < len(lista_dispositivos):
            lista_dispositivos[indice].encender()
        else:
            print("Índice fuera de rango.")

    def Apagar_dispositivo():
        if not lista_dispositivos:
            print("No hay dispositivos para apagar.")
            return
        indice = int(input("Índice a apagar: ")) - 1
        if 0 <= indice < len(lista_dispositivos):
            lista_dispositivos[indice].apagar()
        else:
            print("Índice fuera de rango.")

    while True:
        print("\n--- MENÚ ---")
        print("1. Agregar Teléfono")
        print("2. Agregar Portátil")
        print("3. Mostrar Dispositivos")
        print("4. Eliminar Dispositivo")
        print("5. Encender Dispositivo")
        print("6. Apagar Dispositivo")
        print("7. Salir")

        try:
            opcion = int(input("Selecciona una opción: "))
            if opcion == 1:
                Agregar_telefono()
            elif opcion == 2:
                Agregar_portatil()
            elif opcion == 3:
                Mostrar_dispositivos()
            elif opcion == 4:
                Eliminar()
            elif opcion == 5:
                Encender_dispositivo()
            elif opcion == 6:
                Apagar_dispositivo()
            elif opcion == 7:
                print("Saliendo...")
                break
            else:
                print("Opción inválida.")
        except:
            print("Debes ingresar un número válido.")

if __name__ == '__main__':
    principal()
