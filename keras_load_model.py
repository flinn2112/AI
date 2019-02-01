#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Wed Jan  2 16:11:18 2019

@author: frankkempf
"""

# How to load and use weights from a checkpoint
import sys
from keras.models import Sequential
from keras.layers import Dense
from keras.callbacks import ModelCheckpoint
import matplotlib.pyplot as plt
import numpy
# fix random seed for reproducibility
seed = 7
numpy.random.seed(seed)

# load pima indians dataset
dataset = numpy.loadtxt("test_data.csv", delimiter=' ')
#result_dataset = numpy.loadtxt("test_result.csv", delimiter=' ')
# split into input (X) and output (Y) variables
X = dataset[:,0:24]
print(X)
#da hat noch keiner rausgefunden, wie dataset wirklich funktioniert -  aber so geht's anscheinend - letzte 5 Werte im Array sind die Resultate
Y = dataset[:,24:10000] 
print('Y Result Data: ', Y)
#sys.exit(1)
# create model
model = Sequential()
model.add(Dense(12, input_dim=24, kernel_initializer='uniform', activation='relu'))
#, activation='softmax'
model.add(Dense(10, kernel_initializer='uniform', activation='relu')) 
model.add(Dense(5, kernel_initializer='uniform', activation='sigmoid'))
# load weights
model.load_weights("weights.triage.hdf5")
# Compile model (required to make predictions)
model.compile(loss='categorical_crossentropy', optimizer='adam', metrics=['accuracy'])

print("Created model and loaded weights from file")

# estimate accuracy on whole dataset using loaded weights
scores = model.evaluate(X, Y, verbose=0)


predictions = model.predict(X)
print('--------------------------------------') 
#print(numpy.argmax(predictions[0]))
i = 0
iFails = 0 
iErrs  = 0 
diff   = 0  
#zu dringlich eingeschätzt ist besser als zu schlecht - deshalb, wenn predict < result, dann ist das erstmal nicht schlimm
for p in predictions:    
#    """ wenn vorhersage ungleich trainingsresultat ist, dann fehler hochzaehlen """"
    if  ( numpy.nanargmax(Y[i] != numpy.argmax(p)) ):        
        #   um 2 zu schlecht abgeschätzt
        diff = numpy.argmax(p) - numpy.nanargmax(Y[i])
        if ( diff > 2 ): 
            print("!Fail...", diff )  
            iFails = iFails + 1
        
        
    
        print('[', i + 1, ']') 
        print(X[i], '\nResult:', Y[i], ' <---> Prediction:', p, '\n')
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