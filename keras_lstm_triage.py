#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Tue Mar 19 20:05:36 2019

@author: frankkempf
"""
import keras
import numpy as np
from keras.models import Model
from keras.layers import Input, Dense
from keras.models import Sequential
from keras.callbacks import ModelCheckpoint
from keras.callbacks import EarlyStopping
from keras import optimizers
from keras.optimizers import RMSprop
from keras.losses import categorical_crossentropy
from keras.losses import mean_squared_error
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

fTrain = open(strTrainingFilename) ;

def generate_arrays_from_file(path):
    global iIDX
    global fTrain
    global iGenCount
    while True:
        iGenCount = iGenCount + 1
        if fTrain.closed:
               fTrain = open(strTrainingFilename)
        print("Generator call #:", iGenCount)
        iIDX = 0   
        rDbg     = np.zeros((1, iDataElementCount))
        rTrain     = np.zeros((1, iDataElementCount)) 
        rResult    = np.zeros((1, iResultElementCount))
    #        rTrain     = np.zeros((100, iDataElementCount)) 
    #        rResult    = np.zeros((100, iResultElementCount))
    #    for x in range(20):             
             
             
        line = fTrain.readline()
        if line:
            datas = np.fromstring(line, dtype=float, sep=' ')
            lst = line.split(' ')
            lst.pop()
            lst = lst[0:iDataElementCount]
            lst.append(lst)
        #         rTrain[iIDX] = lst.pop()
            print('---TRAIN--->>>',np.shape(rTrain), rTrain, '<<<-----') 
            rTrain[0] = lst.pop()
            print('---TRAIN--->>>',np.shape(rTrain), rTrain, '<<<-----') 
            iIDX = iIDX + 1
        #         rDbg = np.array(datas,dtype=float)
        #             rDbg = np.append(rDbg, lst.pop())
        #             rResult = np.insert(rResult, 0, datas[iResultElementCount:])
        #             rTrain = tf.convert_to_tensor(rTrain)
        #             print("DATA #:", iIDX, line)
        #    rTrain = lst
            
        #    print('---DEBUG--->>>',np.shape(rDbg), rDbg, '<<<-----') 
        #    rTrain[0]  = datas[0:iDataElementCount]
        #    rResult[0] = datas[iDataElementCount:]
        
        yield(rTrain, rResult)
#                """zurückgeben: training array, result array"""
#            fTrain.close()                   
                
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

model = Sequential()
model.add(Dense(24, input_dim=iDataElementCount, activation='relu'))
model.add(Dense(12, activation='relu'))
model.add(Dense(8, activation='sigmoid'))

#der Ergebnisvektor hat 5 elemente
model.add(Dense(5, activation='softmax'))


#sgd = keras.optimizers.RMSprop(lr=0.001, rho=0.09, epsilon=None, decay=0.0)

#model.compile(sgd, loss=None, metrics=None, loss_weights=None, sample_weight_mode=None, weighted_metrics=None, target_tensors=None)
#, optimizer='adadelta'
#1: model.compile(loss='categorical_crossentropy', optimizer='RMSprop', metrics=['accuracy']) 

optSGD = optimizers.SGD(lr=0.0001, decay=1e-5, momentum=0.8, nesterov=True )
optAdam = keras.optimizers.Adam(lr=0.0001)
optRmsProp = RMSprop(lr=0.001)
currentOptimizer = optRmsProp
#2: model.compile(loss='mean_squared_error', optimizer=sgd)
#categorical_crossentropy
#mean_squared_error
model.compile(loss=categorical_crossentropy, optimizer=currentOptimizer, metrics=['accuracy']) 

#val_acc
checkpoint = ModelCheckpoint(filepath, monitor='acc', verbose=1, save_best_only=True, mode='max')
callbacks_list = [checkpoint]
#loss_acc
#kann auch loss sein
early_stopping = EarlyStopping(monitor='acc', patience=100)


model.fit_generator(generate_arrays_from_file(strTrainingFilename),
                    steps_per_epoch=50, epochs=900, callbacks=[early_stopping]
                    )




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

#1.9.3.29 -> ganzes model wird gespeichert
model.save(filepath)
model.summary()
print('END')

