#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Wed Jan  2 16:11:18 2019

@author: frankkempf
"""

# How to load and use weights from a checkpoint
import sys
from keras.models import Model
from keras.models import Sequential
from keras.layers import Dense
from keras.callbacks import ModelCheckpoint
from keras.models import load_model
import matplotlib.pyplot as plt
import numpy
import h5py
#f. lstm
#lstm_weights.triage.hdf5
#fWeights = "weights.triage.hdf5"
fWeights = "lstm_weights.triage.hdf5"
fModel   = "lstm.triage.hdf5"
# fix random seed for reproducibility
seed = 7
numpy.random.seed(seed)


iDataElementCount = 43
iResultElementCount = 5

# load pima indians dataset
dataset = numpy.loadtxt("test_data.csv", delimiter=' ')
#result_dataset = numpy.loadtxt("test_result.csv", delimiter=' ')
# split into input (X) and output (Y) variables
X = dataset[:,0:iDataElementCount]
print(X)
#da hat noch keiner rausgefunden, wie dataset wirklich funktioniert -  aber so geht's anscheinend - letzte 5 Werte im Array sind die Resultate
Y = dataset[:,iDataElementCount:10000] 
print('Y Result Data: ', Y)
#sys.exit(1)


#model = Sequential()
# load weights
#model.load_weights(fWeights)
#1.9.29.3 das model wird komplett geladen

model = load_model(fModel)
# create model
#print(model)



#model.add(Dense(12, input_dim=iDataElementCount, kernel_initializer='uniform', activation='relu'))
#, activation='softmax'
#model.add(Dense(10, kernel_initializer='uniform', activation='relu')) 
#model.add(Dense(5, kernel_initializer='uniform', activation='sigmoid'))

# Compile model (required to make predictions)
#model.compile(loss='categorical_crossentropy', optimizer='adam', metrics=['accuracy'])

print("Loading model from file [", fModel, "]")



# estimate accuracy on whole dataset using loaded weights
scores = model.evaluate(X, Y, verbose=1)



#with h5py.File(fModel, 'r') as f:
#    predictions = model.predict(X)

predictions = model.predict(X)
print('--------------------------------------') 
#print(numpy.argmax(predictions[0]))
i = 0
iFails = 0 
iErrs  = 0 
diff   = 0  
#zu dringlich eingeschätzt ist besser als zu schlecht - deshalb, wenn predict < result, dann ist das erstmal nicht schlimm
for p in predictions:    
    print("---    Index Training:",  numpy.nanargmax(Y[i]), "  ---   Index Vorhersage: ", numpy.argmax(p) )  
#    """ wenn vorhersage ungleich trainingsresultat ist, dann fehler hochzaehlen """"
    diff = numpy.absolute(numpy.argmax(p) - numpy.nanargmax(Y[i]))
    if( diff != 0 ):        
        #   um 2 zu schlecht abgeschätzt
        
#       also der unterschied in der Indexposition. Die Schätzung hat einen Index, der um 2 von der Realität entfernt ist.        
        
        if ( diff > 1 ):   
            print("!Fail...", diff )  
            iFails = iFails + 1
        else:     
            print("!Warning...", diff ) 
        print('[', i + 1, ']') 
        print(X[i], '\nTraining:', Y[i], ' <---> Prediction:', p, '\n')
        print('Result:', numpy.nanargmax(Y[i]) + 1, ' <---> Prediction:', numpy.argmax(p) + 1, '\n')
        iErrs = iErrs + 1
        """
            kritisch
        """
        
    i = i + 1
    
print('Errors: [', iErrs, '] Critical [', iFails, '] of [', i, '] = ', iErrs / i * 100, '%')    

print('--------------------------------------')
print(predictions)
print("%s: %.2f%%" % (model.metrics_names[1], scores[1]*100))
print(model.metrics_names, scores)