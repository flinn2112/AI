#####################################################
# Trainingsprogramm das neuronale Netz des
# BrickClassifi3r Roboters mit Hilfe von TensorFlow
# 
# Autor: Detlef Heinze 
# Version: 1.3    Use TensorFlow Version >= 1.5
#####################################################

import hcc_utils

import tensorflow as tf  
#2019 hcc
# funktioniert nicht mit tf.placeholder: tf.enable_eager_execution()
from numpy import loadtxt, savetxt, reshape 
import datetime as dt

#Abschalten der Warnmeldungen von TensorFlow, die
#melden, dass die TensorFlow-Library nicht 
#alle Eigenschaften dieser HW nutzt.
import os
os.environ['TF_CPP_MIN_LOG_LEVEL']='2'
import sys

#hcc::medical enhancement class
hcc_trainData   = hcc_utils.acmeEnv('training_data.csv')
hcc_trainResult = hcc_utils.acmeEnv('training_result.csv')
hcc_testData    = hcc_utils.acmeEnv('test_data.csv')
hcc_testResult  = hcc_utils.acmeEnv('test_result.csv')

hcc_utils.makeHey() 
hcc_trainData.load()
hcc_trainResult.load()
hcc_testData.load()
hcc_testResult.load()




print('Training eines Neuronalen Netzes(V1.3)\n')
print('TensorFlow Version: ' + tf.__version__)
start= dt.datetime.now()

# Step 1: Import Training Data (hcc_trainData.m_tData und hcc_trainResult.m_tData)
print('Lese xTrain- und hcc_trainResult.m_tData-Daten')
#hcc_trainData.m_tData= loadtxt('hcc_trainData.m_tData_TwoCubesCylinder375-24.csv')
#hcc_trainResult.m_tData= loadtxt('triageTrainingResults.csv')
#hcc_trainResult.m_tData= loadtxt('hcc_trainResult.m_tData_TwoCubesCylinder375-3.csv')

# Step 2: Import Test Data (hcc_testData.m_tData und hcc_testResult.m_tData)
print('Lese hcc_testData.m_tData und hcc_testResult.m_tData-Daten')
#hcc_testData.m_tData= loadtxt('hcc_testData.m_tData_TwoCubesCylinder300-24.csv')
#hcc_testResult.m_tData= loadtxt('triageTestResults.csv')

# Step 3: Definition der Lernparameter
learning_rate = 0.001
#num_epochs = 250

num_epochs = 200
num_examples = hcc_trainData.m_tData.shape[0]
print('Anzahl der Trainingdaten: '  + repr(hcc_trainData.m_tData.shape[0]))
print('Anzahl der Testdaten: '      + repr(hcc_testData.m_tData.shape[0]))




#sys.exit(1)


#Parameter für das 24 - 6 - 3 neuronale Netz
n_input    = 24   #24 Neuronen für die gemessene Daten
n_hidden_1 = 6 #Größe der versteckten Neuronenschicht
n_classes  = 5  #Größe der ausgebenden Neuronenschicht
fAccuracy  = 0 
               #ist gleich der Anzahl der Klassen
print('NN-Architektur: ' + repr(n_input) +' - ' 
      + repr(n_hidden_1) + ' - ' 
      + repr(n_classes))

# Platzhalter im TensorFlow-Graph 
x = tf.placeholder("float", shape=(None, n_input))
y = tf.placeholder("float", shape=(None, n_classes))

# Step 4: Berechnungsschritte des NN festlegen
def multilayer_perceptron(x, weights, biases):
    # Die versteckte Schicht mit  RELU-Aktivierung
    layer_1 = tf.add(tf.matmul(x, weights['h1']), biases['b1'])
    #versuche mit anderer Kostenfunktion
    layer_1 = tf.nn.sigmoid(layer_1)  
    #layer_1 = tf.nn.relu(layer_1)
  
    # Die Ausgabeschicht mit linearer Aktivierung
    out_layer = tf.matmul(layer_1, weights['out'])+biases['out']
    return out_layer

# Step 5: Initialisiere Model mit Zufallszahlen
weights = {
    'h1': tf.Variable(tf.random_normal([n_input, n_hidden_1])),
    'out': tf.Variable(tf.random_normal([n_hidden_1,n_classes]))
}

biases = {
    'b1': tf.Variable(tf.random_normal([n_hidden_1])),
    'out': tf.Variable(tf.random_normal([n_classes]))
}



# Step 6: Maschinelles Lernen vorbereiten
# Platzhalter für x und Anfangsmodell übergeben
predict = multilayer_perceptron(x, weights, biases)

# Kosten und den Optimizer definieren 
cost = tf.reduce_mean(tf.nn.softmax_cross_entropy_with_logits_v2(logits=predict, labels=y))

optimizer = tf.train.AdamOptimizer(learning_rate=learning_rate).minimize(cost)
#optimizer = tf.train.GradientDescentOptimizer(learning_rate=learning_rate).minimize(cost)
# Variablen von tf initialisieren
init = tf.global_variables_initializer()

#Step 7: Training
print('\n-------------Trainingsphase-----------------')
with tf.Session() as sess:
    sess.run(init)
    for i in range(num_epochs):
        for j in range(num_examples):
            _, c = sess.run([optimizer, cost], 
                            feed_dict={x: [hcc_trainData.m_tData[j]],
                                       y: [hcc_trainResult.m_tData[j]]})
        if i % 25 == 0:
            print('epoch {0}: cost = {1}'.format(i, c))
    print('epoch {0}: cost = {1}'.format(i, c))
    print('Training beendet.')
    duration = (dt.datetime.now() - start)
    print("Dauer: " + str(duration))
    
#Step 8: Berechnung der Treffsicherheit anhand der Testdaten
    correct_prediction = tf.equal(tf.argmax(predict, 1), tf.argmax(y, 1))
    accuracy = tf.reduce_mean(tf.cast(correct_prediction, "float"))
    fAccuracy = accuracy.eval({x: hcc_testData.m_tData, 
                                          y: hcc_testResult.m_tData})
    print('Testergebnis:', accuracy.eval({x: hcc_testData.m_tData, 
                                          y: hcc_testResult.m_tData}))
#Step 9: Ausgabe des berechneten Models
#    print('\n-----------------Modelübersicht----------------------')
#    print('Weights für h1')
#    wh1= weights['h1'].eval(sess)
#    print(wh1)
#    print('\nBiases für b1')
#    bb1= biases['b1'].eval(sess)
#    print(bb1)
#    print('\nWeights für out')
#    wo= weights['out'].eval(sess)
#    print(wo)
#    print('\nBiases für out')
#    bo= biases['out'].eval(sess)
#    print(bo)

#Step 10: Sicherung des berechneten Models als csv und rtf
    print('\n--------------Model wird gespeichert(csv)-------------')
    print('Sichere Weights für h1: NNweights_h1.csv')
    savetxt('NNweights_h1.csv', wh1, fmt='%10.8f', delimiter=' ')
    
    
    
    print('Sichere Biases für b1: NNbiases_b1.csv')
    savetxt('NNbiases_b1.csv', bb1, fmt='%10.8f', delimiter=' ')
    xxx = hcc_utils.acmeEnv('NNbiases_b1.hcc.csv')
#    xxx.m_Data = bb1
    xxx.setData(bb1)
#    hcc_utils.acmeEnv.save('NNbiases_b1.hcc.csv', bb1, '%10.8f', ' ', None)   
#

    xxx.saveData( '%10.8f', ' ', None) 
#   TypeError: Mismatch between array dtype ('float32') and format specifier ('%10.8f')
    print('Sichere Weights für out: NNweights_out.csv')
    savetxt('NNweights_out.csv', wo, fmt='%10.8f', delimiter=' ')
    print('Sichere Biases für out: NNbiases_out.csv')
    savetxt('NNbiases_out.csv', bo, fmt='%10.8f', delimiter=' ')

    print('\n--Model wird gespeichert(rtf für Lego Mindstorms EV3)--')
    #Format: <Anzal Zeilen>CR<Anzahl Spalten>CR<{<aReal>CR}*
    print('Sichere Weights für h1: NNweights_h1.rtf')  
    tmpArray = reshape(wh1, (wh1.shape[0] * wh1.shape[1],))
    result= [wh1.shape[0],wh1.shape[1]] + tmpArray.tolist()
    savetxt('NNweights_h1.rtf', result, fmt='%10.8f', delimiter='\r', newline='\r')
     
    print('Sichere Biases für b1: NNbiases_b1.rtf')  
    result= [1,bb1.shape[0]] + bb1.tolist()
    savetxt('NNbiases_b1.rtf', result, fmt='%10.8f', delimiter='\r', newline='\r')

    print('Sichere Weights für out: NNweights_out.rtf')  
    tmpArray = reshape(wo, (wo.shape[0] * wo.shape[1],))
    result= [wo.shape[0],wo.shape[1]] + tmpArray.tolist()
    savetxt('NNweights_out.rtf', result, fmt='%10.8f', delimiter='\r', newline='\r')

    print('Sichere Biases für out: NNbiases_out.rtf')  
    result= [1,bo.shape[0]] + bo.tolist()
    savetxt('NNbiases_out.rtf', result, fmt='%10.8f', delimiter='\r', newline='\r')


    print('Testergebnis ------->', fAccuracy, '<--------') 

    print('Model gesichert. Programmende.')
