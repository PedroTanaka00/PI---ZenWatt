#include <EmonLib.h>
#include <SPI.h>

EnergyMonitor emon1;

//Tensao da rede eletrica
int rede = 127;

float pino_sct = 34;

void setup(){
  Serial.begin(9600);
  // 2000/25 = 80
  emon1.current(pino_sct, 80);    
}

void loop(){

  //Calcula a corrente
  double Irms = emon1.calcIrms(1480);
  double potencia = rede * Irms;  

  //Linha de Saída
  Serial.print(Irms); 
  Serial.print(",");
  Serial.print(potencia);
  Serial.print("\n");

  delay(10000);
}