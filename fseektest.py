#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Sun May 12 08:18:02 2019
Position reading in file
@author: frankkempf
"""

import numpy as np
import sys
import re

strTrainingFilename   = 'training_data.csv'
strTestFilename       = 'test_data.csv'
strEvaluationFilename = 'eval_data.csv'
filepath              = "lstm.triage.hdf5"
iDataElementCount     = 43
iResultElementCount   =  5
momentum              = 0.09
iGenCount             = 0
iPatience             = 100 
iEpochs               = 900
iStepsPerEpoch        = 10
iBatchSize             = 10
fTrain = open(strTrainingFilename) 



def generate_arrays_from_file2(path):
    global iIDX
    global fTrain
    global fLog
    global iGenCount
    fTrain = open(path)
    
    
    iIDX = 0
    strOut = ''
    iGenCount = iGenCount + 1
    if fTrain.closed:
           fTrain = open(path)
    print("Generator call #: ", iGenCount)
       
    rDbg       = np.zeros((1, iDataElementCount))
    rTrain     = np.zeros((iBatchSize, iDataElementCount)) 
    rResult    = np.zeros((iBatchSize, iResultElementCount))

    for x in range(iBatchSize): 
        line = fTrain.readline()
        
        strMsg = 'No line at #' + str(iIDX)
        if not line:
#                raise Exception(strMsg)
            fTrain = open(strTrainingFilename)
            line = fTrain.readline()
            iIDX = 0
        
        if line:
            p = re.compile(r'\s$')
            p.sub('', line)
            datas = np.fromstring(line, dtype=float, sep=' ')
#            print(datas)
#            np.random.shuffle(datas)
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
#            print('---RESULT-->>>',np.shape(rResult), rResult[iIDX], '<<<-----') 
#            rResult = np.append(rResult, datas[iDataElementCount:])
        else:
            print('NO DATA at #', iIDX)                 
            iIDX = 0
        iIDX = iIDX + 1
        strOut = str(iIDX) + '#'  + str(rResult)
#mit dem Log kann verglichen werden, ob wirklich passende Daten zurückgeliefert wurden            
        fLog.write(strOut)
    
    print('---TRAIN--->>>',np.shape(rTrain), rTrain, '<<<-----') 
    print('---RESULT-->>>',np.shape(rResult), rResult, '<<<-----') 
    return(rTrain, rResult)
generate_arrays_from_file2(strTrainingFilename)
print("END!") 