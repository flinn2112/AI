#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Fri May  3 08:04:01 2019

@author: frankkempf
"""

#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Tue Mar 19 20:05:36 2019

@author: frankkempf
"""
import keras
import numpy as np
import tensorflow as tf
import sys
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
filepath              = "1lstm.triage.hdf5"
iDataElementCount     =   43
iResultElementCount   =    5
#HYPERPARAMETERS
iMomentum             = 0.09
iEpochs               = 1
iEpochSteps           = 1
iEvalSteps            = 100
iPredictSteps         = 100
iPatience             = 25
iLearnRate            = 0.0001
iSGDDecay             = 1e-2
optSGD = optimizers.SGD(lr=iLearnRate, decay=iSGDDecay, momentum=iMomentum, nesterov=True )
optAdam = keras.optimizers.Adam(lr=iLearnRate)
optRmsProp = RMSprop(lr=iLearnRate)
currentOptimizer = optAdam

fTraining = open(strTrainingFilename) 
fTest     = open(strTestFilename) 
fEval     = open(strEvaluationFilename)
iIDX = 0
iGenCount = 0
#Anzahl gelesener Sätze in einer Epoche
iNumRecs  = 0 


def generate_arrays_from_file(path):    
    global iIDX
    global fTraining
    global iGenCount
#    while True:
    iGenCount = iGenCount + 1
    if fTraining.closed:
           fTraining = open(strTrainingFilename)
    print("Generator call #:", iGenCount)
    iIDX = 0   
    rDbg     = np.zeros((1, iDataElementCount))
    rTrain     = np.zeros((1, iDataElementCount)) 
    rResult    = np.zeros((1, iResultElementCount))
#        rTrain     = np.zeros((100, iDataElementCount)) 
#        rResult    = np.zeros((100, iResultElementCount))
    for x in range(10):             
         iIDX = iIDX + 1
         
         line = fTraining.readline()
         datas = np.fromstring(line, dtype=float, sep=' ')
         lst = line.split(' ')
         lst.pop()
#         lst.reshape(1, 43)
         lst.append(lst)
#         rDbg = np.array(datas,dtype=float)
#             rDbg = np.append(rDbg, lst.pop())
#             rResult = np.insert(rResult, 0, datas[iResultElementCount:])
#             rTrain = tf.convert_to_tensor(rTrain)
#             print("DATA #:", iIDX, line)
    
    print('---LST--->>>',np.shape(lst), lst, '<<<-----') 
    print('---DEBUG--->>>',np.shape(rDbg), rDbg, '<<<-----') 
    rTrain[0]  = datas[0:iDataElementCount]
    rResult[0] = datas[iDataElementCount:]
#    rTrain.reshape(43,1000)
        
        
        
        
generate_arrays_from_file(' ')        
        
        