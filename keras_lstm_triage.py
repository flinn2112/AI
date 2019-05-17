#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Tue Mar 19 20:05:36 2019

@author: frankkempf
"""
import keras
import numpy as np
import sys
import re
from keras.models import Model
from keras.layers import Input, Dense
from keras.models import Sequential
from keras.callbacks import ModelCheckpoint
from keras.callbacks import EarlyStopping
from keras import optimizers
from keras.optimizers import RMSprop
from keras.losses import categorical_crossentropy
from keras.losses import mean_squared_error
from keras.callbacks import History 

import matplotlib as mpl
import matplotlib.pyplot as plt

import h5py
#'training.xp.csv'
strTrainingFilename   = 'training_data.csv'
strTestFilename       = 'test_data.csv'
strEvaluationFilename = 'eval_data.csv'
filepath              = "lstm.triage.hdf5"
iDataElementCount     = 43
iResultElementCount   =  5
momentum              = 0.09
iGenCount             = 0
iPatience             = 100 
iEpochs               = 250
iStepsPerEpoch        = 2000

iBatchSize             = 2000
#int(2000 / iStepsPerEpoch)
fTrain = open(strTrainingFilename) 
fLog   = open('log.txt', "w")

keras.callbacks.TensorBoard(log_dir='./logs', histogram_freq=0, batch_size=32, write_graph=True, write_grads=False, write_images=False, embeddings_freq=0, embeddings_layer_names=None, embeddings_metadata=None, embeddings_data=None, update_freq='epoch')

optSGD = optimizers.SGD(lr=0.0001, decay=1e-5, momentum=0.8, nesterov=True )
optAdam = keras.optimizers.Adam(lr=0.0001)
optRmsProp = RMSprop(lr=0.0001)
currentOptimizer = optRmsProp
 
#nur xp
"""
dataset = np.loadtxt(strTrainingFilename)
X = dataset[:,0:43]
print(X.shape) 
#orginalwert war ,8
Y = dataset[:,43:]
"""

#gibt immer genau einen Datensatz aus dem File zurück
def generate_arrays_from_file(path):
    global iIDX
    global fTrain
    global fLog
    global iGenCount
    while True:
        iGenCount = iGenCount + 1
        if fTrain.closed:
               fTrain = open(strTrainingFilename)
        print("Generator call #:", iGenCount)
        iIDX = 0   
        rDbg       = np.zeros((1, iDataElementCount))
        rTrain     = np.zeros((1, iDataElementCount)) 
        rResult    = np.zeros((1, iResultElementCount))
             
             
        line = fTrain.readline()
        if line:
            p = re.compile('\s')
            p.sub('', line)
            datas = np.fromstring(line, dtype=float, sep=' ')
            lst = line.split(' ')
            lst.pop()
            lst = lst[0:iDataElementCount]
            lst.append(lst)
            
        
            rTrain[0] = lst.pop()
            

            rRes = line.split(' ')
            rRes = rRes[iDataElementCount:]
            rResult[0] = rRes
            
#            print('---TRAIN--->>>',np.shape(rTrain), rTrain, '<<<-----') 
            iIDX = iIDX + 1        
        else:
            print('NO DATA') 
#        print('---TRAIN--->>>',np.shape(rTrain), rTrain, '<<<-----') 
#        print('---RESULT-->>>',np.shape(rResult), rResult, '<<<-----') 
        
        yield(rTrain, rResult)        
        


def generate_arrays_from_file2(path):
    global iIDX
    global fTrain
    global fLog
    global iGenCount
    global X, Y
    fTrain = open(path)
    
    while True:
        iIDX = 0
        strOut = ''
        iGenCount = iGenCount + 1
        
        print("Generator call #: ", iGenCount)
        if fTrain.closed:
            fTrain = open(path)
           
        rDbg       = np.zeros((1, iDataElementCount))
        rTrain     = np.zeros((1, iDataElementCount)) 
        rResult    = np.zeros((1, iResultElementCount))
    
#        for x in range(iBatchSize): 
        line = fTrain.readline()
        
        strMsg = 'No line at #' + str(iIDX)
        
        
        if line:
            p = re.compile(r'\s$')
            p.sub('', line)
            datas = np.fromstring(line, dtype=float, sep=' ')
#                print(datas)
#                np.random.shuffle(datas)
            lst = line.split(' ')
            lst.pop()
            lst = lst[0:iDataElementCount]
            lst.append(lst)
            rTrain[iIDX] = datas[0:iDataElementCount] 
#                rTrain[iIDX] = lst.pop()
#                rRes = line.split(' ')
#                rRes = rRes[iDataElementCount:]
#                rResult[iIDX] = rRes
            rResult[iIDX] = datas[iDataElementCount:]
            
#                rResult[iIDX,] = np.append(rResult, datas[iDataElementCount:])
            
        else:
            print('NO DATA at #', iIDX)                 
            iIDX = 0
        iIDX = iIDX + 1
            
        strOut = str(iIDX) + '#'  + str(rResult) + '\n'
#mit dem Log kann verglichen werden, ob wirklich passende Daten zurückgeliefert wurden            
        fLog.write(strOut)
        
        print('---TRAIN--->>>', np.shape(rTrain), rTrain, '<<<-----') 
#        print('---RESULT-->>>', np.shape(rResult), rResult, '<<<-----') 
        
        yield(rTrain, rResult)
#        yield(X, Y)

                
def async_get_for_prediction(path):
    while True:
        with open(path) as f:
            for line in f:
                rTest  = np.zeros((1, iDataElementCount)) 
                rResult = np.zeros((1, iResultElementCount)) 
                datas  = np.fromstring(line, dtype=float, sep=' ')                
                rTest[0] = datas[0:iDataElementCount]
                rResult[0] = datas[iDataElementCount:]     
#                print('---TRAIN--->>>',np.shape(rTest), rTest, '<<<-----') 
#                print('---RESULT-->>>',np.shape(rResult), rResult, '<<<-----') 
                yield(rTest, rResult)
                """zurückgeben: training array, result array"""
            
            
            
def async_get_for_evaluation(path):
    while True:
        with open(path) as f:
            for line in f:
                rTest  = np.zeros((1, iDataElementCount)) 
                rResult = np.zeros((1, iResultElementCount)) 
                datas  = np.fromstring(line, dtype=float, sep=' ')                
                rTest[0] = datas[0:iDataElementCount]
                rResult[0] = datas[iDataElementCount:]   
                print('---eval TRAIN--->>>',np.shape(rTest), rTest, '<<<-----') 
                print('---eval RESULT-->>>',np.shape(rResult), rResult, '<<<-----') 
                yield(rTest, rResult)
                """zurückgeben: training array, result array"""
            f.close()    


class custom_callbacks(keras.callbacks.Callback):
    iNumEpochs = 0
    def __init__(self):
        self.iNumEpochs = 0
    def on_train_begin(self, logs={}):
        self.losses = []

    def on_batch_end(self, batch, logs={}):
        self.losses.append(logs.get('loss'))
        print("BATCH END")
        
    def on_epoch_begin(a, b, c):
        custom_callbacks.iNumEpochs = custom_callbacks.iNumEpochs + 1
        print('#', b, ' on_epoch_begin')  
#        sys.exit()
        fTrain = open(strTrainingFilename)
        pass

    def on_epoch_end(a,b, c):
        fTrain.close()
        pass    
    
    def stats(self):
        print('#', custom_callbacks.iNumEpochs, ' executed') 
        pass
ccb = custom_callbacks()
model = Sequential()
model.add(Dense(24, input_dim=iDataElementCount, activation='relu'))
model.add(Dense(18, input_dim=iDataElementCount, activation='relu'))
model.add(Dense(10, activation='relu'))
model.add(Dense(8, activation='sigmoid'))

#der Ergebnisvektor hat 5 elemente
model.add(Dense(5, activation='softmax'))


#sgd = keras.optimizers.RMSprop(lr=0.001, rho=0.09, epsilon=None, decay=0.0)

#model.compile(sgd, loss=None, metrics=None, loss_weights=None, sample_weight_mode=None, weighted_metrics=None, target_tensors=None)
#, optimizer='adadelta'
#1: model.compile(loss='categorical_crossentropy', optimizer='RMSprop', metrics=['accuracy']) 


#2: model.compile(loss='mean_squared_error', optimizer=sgd)
#categorical_crossentropy
#mean_squared_error
model.compile(loss=categorical_crossentropy, optimizer=currentOptimizer, metrics=['accuracy']) 

#val_acc
checkpoint = ModelCheckpoint(filepath, monitor='val_acc', verbose=1, save_best_only=True, mode='max')
callbacks_list = [checkpoint]
#loss_acc
#kann auch loss sein
early_stopping = EarlyStopping(monitor='acc', patience=iPatience)


h = model.fit_generator(generate_arrays_from_file2(strTrainingFilename),
                    steps_per_epoch=iStepsPerEpoch, epochs=iEpochs, callbacks=[early_stopping, ccb]
                    )
#history = History()
# list all data in history

print(h.history.keys())
print("Epochs: ", custom_callbacks.iNumEpochs)
print('LOSS: ',h.history['loss'])
print('ACC: ',h.history['acc'])
#print(h.history['val_acc'])

"""
sys.exit()


# doc: predict_generator(generator, steps=None, callbacks=None, max_queue_size=10, workers=1, use_multiprocessing=False, verbose=0)
predictions = model.predict_generator(async_get_for_prediction(strTestFilename), steps=50000)
print('First prediction:', predictions[0])

# evaluate the model
#evaluate_generator(generator, steps=None, callbacks=None, max_queue_size=10, workers=1, use_multiprocessing=False, verbose=0)
scores = model.evaluate_generator(async_get_for_evaluation(strEvaluationFilename), steps=500)
#scores = model.evaluate_generator()
#index startet bei 0
print('Predicted Class: ', np.argmax(predictions[0])  - 1, " Act") 

print("\n%s: %.2f%%" % (model.metrics_names[1], scores[1]*100))
print('XP Scores:', scores)

#model.save_weights(filepath)


"""
#1.9.3.29 -> ganzes model wird gespeichert
model.save(filepath)
model.summary()
#print(history.losses)
ccb.stats()
fLog.close()


plt.plot(h.history['loss'], label='loss')
plt.plot(h.history['acc'], label='acc')
plt.legend(loc='best')
plt.show()

print('END')

