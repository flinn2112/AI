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




def generate_arrays_from_file(path):
    while True:
        with open(path) as f:
            for line in f:
                # create numpy arrays of input data
                # and labels, from each line in the file
 #               x1, x2, y = process_line(line)
 #               yield ({'input_1': x1, 'input_2': x2}, {'output': y}) 
#x                np.array( line )

                rTrain  = np.zeros((1, 44)) 
                rResult = np.zeros((1, 5)) 
                datas  = np.fromstring(line, dtype=float, sep=' ')
                
                rTrain[0] = datas[0:44]
                rResult[0] = datas[44:]
                print( rTrain )
                
#                print(np.shape(datas[0:44]), datas[0:44])
                train  = datas[0:44]
                result = datas[44:]
                print('---TRAIN--->>>',np.shape(rTrain), rTrain, '<<<-----') 
                print('---RESULT-->>>',np.shape(rResult), rResult, '<<<-----') 
#                yield(train.reshape(44,1), result) 
#                yield ({'input_1': train, 'input_2': result}, {'output': result}) 
# geht auch nicht - gibt immer falschen shape 1,                
                yield(rTrain, rResult)
                """zurückgeben: training array, result array"""
            f.close()                   
                


model = Sequential()
model.add(Dense(11, input_dim=44, activation='relu'))
model.add(Dense(10, activation='relu'))
#der Ergebnisvektor hat 5 elemente
model.add(Dense(5, activation='sigmoid'))


sgd = keras.optimizers.RMSprop(lr=0.001, rho=0.9, epsilon=None, decay=0.0)

#model.compile(sgd, loss=None, metrics=None, loss_weights=None, sample_weight_mode=None, weighted_metrics=None, target_tensors=None)
model.compile(loss='categorical_crossentropy', optimizer='adadelta')
model.fit_generator(generate_arrays_from_file('training.xp.csv'),
                    steps_per_epoch=10000, epochs=10)




print('END')

