#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Created on Mon Dec 31 08:52:21 2018
Hi
@author: frankkempf

"""

import sys
import tensorflow as tf  
from numpy import loadtxt, savetxt, reshape 
import datetime as dt
# Create your first MLP in Keras
from tensorflow import keras as keras
from keras.models import Sequential
from keras.layers import Dense
from keras.callbacks import ModelCheckpoint
import numpy as np

filepath            = "lstm.triage.hdf5"
iCount = 0 
# fix random seed for reproducibility
np.random.seed(7)
# load pima indians dataset
dataset = np.loadtxt("training_data.csv", delimiter=" ")
#result_dataset = np.loadtxt("training_result.csv", delimiter=" ")
# split into input (X) and output (Y) variables
#originalwert war 0:8
X = dataset[:,0:43]
print(X.shape) 
#orginalwert war ,8
Y = dataset[:,43:]
print(X)
print(Y)
#sys.exit(1)



# create model
model = Sequential()
#dim war im Beispiel 8
model.add(Dense(12, input_dim=43, activation='relu'))
model.add(Dense(10, activation='relu'))
#der Ergebnisvektor hat 5 elemente
model.add(Dense(5, activation='sigmoid'))

#sgd = keras.optimizers.SGD(lr=0.01, clipvalue=0.5)
#sgd = keras.optimizers.RMSprop(lr=0.001, rho=0.9, epsilon=None, decay=0.0)

# Compile model

#'adam'
model.compile(loss='categorical_crossentropy', optimizer='RMSprop', metrics=['accuracy']) 



# Fit the model
#der Pfad f. die resultierenden Gewichte
filepath="weights.triage.hdf5"
checkpoint = ModelCheckpoint(filepath, monitor='val_acc', verbose=1, save_best_only=True, mode='max')
callbacks_list = [checkpoint]

#model.fit(X, Y, epochs=75, batch_size=50)

#Best(500 Epochs, Dense 8): 0.01 -> 72.46%
#Overfitting: 0.001 -> 90.74%
#!10 Dense -> 96.22%
model.fit(X, Y, validation_split=0.001, epochs=900, batch_size=500, callbacks=callbacks_list, verbose=0)


predictions = model.predict(X)
print('First prediction:', predictions[0])
for p in predictions:
    iCount = iCount + 1
    if (iCount % 10 == 0):
        print(iCount, ': Predicted Level: ', np.argmax(p) + 1 )


print('Predicted INDEX: ', np.argmax(predictions[0]))
# evaluate the model
scores = model.evaluate(X, Y)
model.save(filepath)
print("\n%s: %.2f%%" % (model.metrics_names[1], scores[1]*100))
print(scores)
