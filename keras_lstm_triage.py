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

def generate_arrays_from_file(path):
    while True:
        with open(path) as f:
            for line in f:
                # create numpy arrays of input data
                # and labels, from each line in the file
 #               x1, x2, y = process_line(line)
 #               yield ({'input_1': x1, 'input_2': x2}, {'output': y}) 
#x                np.array( line )

                rTrain  = np.zeros((1, iDataElementCount)) 
                rResult = np.zeros((1, iResultElementCount)) 
                datas  = np.fromstring(line, dtype=float, sep=' ')
                
                rTrain[0] = datas[0:iDataElementCount]
                rResult[0] = datas[iDataElementCount:]
#               print( rTrain )
                
#                print(np.shape(datas[0:44]), datas[0:44])
#                train  = datas[0:44]
#                result = datas[44:]
#                print('---TRAIN--->>>',np.shape(rTrain), rTrain, '<<<-----') 
#                print('---RESULT-->>>',np.shape(rResult), rResult, '<<<-----') 
#                yield(train.reshape(44,1), result) 
#                yield ({'input_1': train, 'input_2': result}, {'output': result}) 
# geht auch nicht - gibt immer falschen shape 1,                
                yield(rTrain, rResult)
                """zurückgeben: training array, result array"""
            f.close()                   
                
def async_get_for_prediction(path):
    while True:
        with open(path) as f:
            for line in f:
                rTest  = np.zeros((1, iDataElementCount)) 
                rResult = np.zeros((1, iResultElementCount)) 
                datas  = np.fromstring(line, dtype=float, sep=' ')                
                rTest[0] = datas[0:iDataElementCount]
                rResult[0] = datas[iDataElementCount:]     
                print('---TRAIN--->>>',np.shape(rTest), rTest, '<<<-----') 
                print('---RESULT-->>>',np.shape(rResult), rResult, '<<<-----') 
                yield(rTest, rResult)
                """zurückgeben: training array, result array"""
            f.close()   
            
            
def async_get_for_evaluation(path):
    while True:
        with open(path) as f:
            for line in f:
                rTest  = np.zeros((1, iDataElementCount)) 
                rResult = np.zeros((1, iResultElementCount)) 
                datas  = np.fromstring(line, dtype=float, sep=' ')                
                rTest[0] = datas[0:iDataElementCount]
                rResult[0] = datas[iDataElementCount:]   
                print('---TRAIN--->>>',np.shape(rTest), rTest, '<<<-----') 
                print('---RESULT-->>>',np.shape(rResult), rResult, '<<<-----') 
                yield(rTest, rResult)
                """zurückgeben: training array, result array"""
            f.close()               

model = Sequential()
model.add(Dense(12, input_dim=iDataElementCount, activation='relu'))

model.add(Dense(8, activation='relu'))

#der Ergebnisvektor hat 5 elemente
model.add(Dense(5, activation='sigmoid'))


#sgd = keras.optimizers.RMSprop(lr=0.001, rho=0.09, epsilon=None, decay=0.0)

#model.compile(sgd, loss=None, metrics=None, loss_weights=None, sample_weight_mode=None, weighted_metrics=None, target_tensors=None)
#, optimizer='adadelta'
#1: model.compile(loss='categorical_crossentropy', optimizer='RMSprop', metrics=['accuracy']) 

sgd = optimizers.SGD(lr=0.001, decay=1e-6, momentum=0.9, nesterov=True)
optAdam = keras.optimizers.Adam(lr=0.001)
optRmsProp = RMSprop(lr=0.0001)
currentOptimizer = optRmsProp 
#2: model.compile(loss='mean_squared_error', optimizer=sgd)
#categorical_crossentropy
model.compile(loss=mean_squared_error, optimizer=currentOptimizer, metrics=['accuracy']) 
model.summary()
checkpoint = ModelCheckpoint(filepath, monitor='val_acc', verbose=1, save_best_only=True, mode='max')
callbacks_list = [checkpoint]
#loss_acc
#kann auch loss sein
early_stopping = EarlyStopping(monitor='acc', patience=10)


model.fit_generator(generate_arrays_from_file(strTrainingFilename),
                    steps_per_epoch=50, epochs=1000, callbacks=[early_stopping]
                    )




# doc: predict_generator(generator, steps=None, callbacks=None, max_queue_size=10, workers=1, use_multiprocessing=False, verbose=0)
predictions = model.predict_generator(async_get_for_prediction(strTestFilename), steps=5)
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

print('END')

