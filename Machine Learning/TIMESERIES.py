#importing the required libraries
import numpy as np
import matplotlib.pyplot as plt
import pandas as pd
from keras.models import Sequential
from keras.layers import Dense
from keras.layers import LSTM
from sklearn.preprocessing import MinMaxScaler
from sklearn.metrics import mean_squared_error
import math
import json
import csv

#Function to convert .csv into .json and also return it
def tojson():
    
    csvfile = open('Keyboard.csv', 'r')
    
    fieldnames = ("Date","price","discount","quantity")
    reader = csv.DictReader( csvfile, fieldnames)
    out = json.dumps( [ row for row in reader ] )
    return out




#Loading the dataset
dataset_train = pd.read_csv('Keyboard.csv')

#Splitting it into variables for independent and dependent units
x_training_set = dataset_train.iloc[:, 1:4].values
y_training_set = dataset_train.iloc[:,-1:].values

#One Hot Encoding 
one_hot = pd.get_dummies(x_training_set['inventory_id'])
# Drop column B as it is now encoded
x_training_set = x_training_set.drop('inventory_id', axis=1)
# Join the encoded df
x_training_set = x_training_set.join(one_hot)
#Feature scaling for standardized results
sc = MinMaxScaler(feature_range = (0, 1))
x_training_set_scaled = sc.fit_transform(x_training_set)
y_training_set_scaled=sc.fit_transform(y_training_set)

X_train = []
y_train = []

#Converting into array
X_train = np.array(x_training_set_scaled) 
y_train = np.array(y_training_set_scaled)

#adding the third dimension
X_train = np.reshape(X_train, (X_train.shape[0], X_train.shape[1], 1))


#Importing libraties for the LSTM model
from keras.models import Sequential
from keras.layers import Dense
from keras.layers import LSTM
from keras.layers import Dropout

# Initialising the RNN
regressor = Sequential()

# Adding the first LSTM layer 
regressor.add(LSTM(units = 50, return_sequences = True, input_shape = (X_train.shape[1], 1)))

# Adding a second LSTM layer 
regressor.add(LSTM(units = 50, return_sequences = True))

# Adding a third LSTM layer 
regressor.add(LSTM(units = 50, return_sequences = True))

# Adding a fourth LSTM layer
regressor.add(LSTM(units = 50))

# Adding the output layer
regressor.add(Dense(units = 1))

# Compiling the RNN
regressor.compile(optimizer = 'adam', loss = 'mean_squared_error')

# Fitting the RNN to the Training set
#Training with 300 epochs
regressor.fit(X_train, y_train, epochs = 300, batch_size = 32)

dataset_test = pd.read_csv('Keyboard_Test.csv')
real_sales = dataset_test.iloc[:, 1:4].values

#Adding datasets to a frame
frames=[dataset_train,dataset_test]
#Concating the dataset
total_dataset=pd.concat(frames)
#Dropping the colomns
total_dataset=total_dataset.drop(['quantity'],axis=1)
total_dataset=total_dataset.drop(['Date'],axis=1)

#Taking all values
inputs= total_dataset.values

#Applying feature scaling
inputs = sc.transform(inputs)
X_test = []
X_test = np.array(inputs)
#adding third dimension
X_test = np.reshape(X_test, (X_test.shape[0], X_test.shape[1], 1))
predicted_sales = regressor.predict(X_test)
predicted_sales = sc.inverse_transform(predicted_sales)

    
#Return jsonfile
tojson()
